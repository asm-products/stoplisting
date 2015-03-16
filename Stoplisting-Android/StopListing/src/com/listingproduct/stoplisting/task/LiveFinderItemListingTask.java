package com.listingproduct.stoplisting.task;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.URI;
import java.net.URISyntaxException;
import java.util.Timer;
import java.util.TimerTask;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.CoreProtocolPNames;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.PopupWindow;
import android.widget.TextView;

import com.listingproduct.stoplisting.Action;
import com.listingproduct.stoplisting.Application;
import com.listingproduct.stoplisting.LiveFinderResultActivity;
import com.listingproduct.stoplisting.R;
import com.listingproduct.stoplisting.global.GlobalDefine;
import com.listingproduct.stoplisting.global.GlobalFunc;

public class LiveFinderItemListingTask {

	public static int MAX_TRY_COUNT = 6;
	int mCurTryCont = 0;

	Application mApplication;
	int mLiveFinderId;
	int mUserId;

	Timer mtimer;
	
	String mTaskSuccessMsg = "Successfully Listed Item.";
	String mTaskFailureMsg = "Listing Item Failure. Please try again later.";
	String Tag = "LiveFinderTask";

	public LiveFinderItemListingTask(Context context, int liveFinderId) {
		mApplication = (Application) context.getApplicationContext();
		mLiveFinderId = liveFinderId;
		mUserId = mApplication.getUserId();
	}

	TimerTask mTimeTask = new TimerTask() {
		public void run() {

			boolean bSuccess = false;
			String strLiveFinderItemListingUrl = String.format(
					GlobalDefine.SiteURLs.liveFinderItemListingURL,
					mLiveFinderId, mUserId);

			// This method for HttpConnection
			BufferedReader bufferedReader = null;
			try {
				// --------------Http Request for getting result--------------//

				HttpClient client = new DefaultHttpClient();
				client.getParams().setParameter(CoreProtocolPNames.USER_AGENT,
						"android");
				HttpGet request = new HttpGet();
				request.setHeader("Content-Type", "text/plain; charset=utf-8");
				request.setURI(new URI(strLiveFinderItemListingUrl));
				HttpResponse response = client.execute(request);
				bufferedReader = new BufferedReader(new InputStreamReader(
						response.getEntity().getContent()));

				StringBuffer stringBuffer = new StringBuffer("");
				String line = "";

				String NL = System.getProperty("line.separator");
				while ((line = bufferedReader.readLine()) != null) {
					stringBuffer.append(line + NL);
				}
				bufferedReader.close();
				String page = stringBuffer.toString();

				// --------------Json Parsing--------------//
				page = page.trim();

				try {
					JSONObject itemListingResult = new JSONObject(page);
					if (itemListingResult != null) {
						String strLoginStatus = itemListingResult
								.getString("status");
						int loginStatus = Integer.parseInt(strLoginStatus);

						if (loginStatus == 200) {
							bSuccess = false;
						}
					}
				} catch (JSONException e) {
					e.printStackTrace();
					bSuccess = false;
				}

			} catch (URISyntaxException | IOException e) {
				GlobalFunc.viewLog(Tag,
						"Error occured in reading the plan data.", true);
				GlobalFunc.viewLog(Tag, e.toString(), true);
				bSuccess = false;
			} finally {
				if (bufferedReader != null) {
					try {
						bufferedReader.close();
					} catch (IOException e) {
						GlobalFunc.viewLog(Tag, e.toString(), true);
					}
				}
			}

			if (bSuccess) {
				// Stop current task
				stop();

				final Activity activity = mApplication.getCurrentActivity();
				if (activity != null) {
					activity.runOnUiThread(new Runnable() {

						@Override
						public void run() {
							// Show popup Message
							View popupView = activity
									.getLayoutInflater()
									.inflate(
											R.layout.livefinder_alert_screen,
											null);
							final PopupWindow mPopupWindow = new PopupWindow(
									popupView,
									LinearLayout.LayoutParams.MATCH_PARENT,
									LinearLayout.LayoutParams.WRAP_CONTENT);

							mPopupWindow.setAnimationStyle(R.style.popup);
							mPopupWindow.showAtLocation(popupView, Gravity.TOP,
									0, 50);
							
							TextView alertMsgTv = (TextView) popupView
									.findViewById(R.id.alertMsgTv);
							alertMsgTv.setText(mTaskSuccessMsg);
							Button okBtn = (Button) popupView
									.findViewById(R.id.okBtn);
							okBtn
									.setOnClickListener(new View.OnClickListener() {

										@Override
										public void onClick(View v) {
											if (mPopupWindow != null
													&& mPopupWindow.isShowing()) {
												mPopupWindow.dismiss();
											}
										}
									});
						}
					});
				}

			} else {
				mCurTryCont++;
				if (mCurTryCont == MAX_TRY_COUNT) {
					stop();
					final Activity activity = mApplication.getCurrentActivity();
					if (activity != null) {
						activity.runOnUiThread(new Runnable() {

							@Override
							public void run() {
								// Show popup Message
								View popupView = activity
										.getLayoutInflater()
										.inflate(
												R.layout.livefinder_alert_screen,
												null);
								final PopupWindow mPopupWindow = new PopupWindow(
										popupView,
										LinearLayout.LayoutParams.MATCH_PARENT,
										LinearLayout.LayoutParams.WRAP_CONTENT);

								mPopupWindow.setAnimationStyle(R.style.popup);
								mPopupWindow.showAtLocation(popupView, Gravity.TOP,
										0, 50);
								
								TextView alertMsgTv = (TextView) popupView
										.findViewById(R.id.alertMsgTv);
								alertMsgTv.setText(mTaskFailureMsg);
								Button okBtn = (Button) popupView
										.findViewById(R.id.okBtn);
								okBtn
										.setOnClickListener(new View.OnClickListener() {

											@Override
											public void onClick(View v) {
												if (mPopupWindow != null
														&& mPopupWindow.isShowing()) {
													mPopupWindow.dismiss();
												}
											}
										});
							}
						});
					}
				}
			}
		}
	};

	public void start() {
		stop();

		mtimer = new Timer();
		mtimer.schedule(mTimeTask, 0, 60000); // every one minute, MAX_TRY_NUMBER(6) times
	}

	public void stop() {
		if (mtimer != null) {
			mtimer.cancel();
			mtimer = null;
		}
	}
}
