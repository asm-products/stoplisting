package com.listingproduct.stoplisting.task;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.URI;
import java.net.URISyntaxException;
import java.util.ArrayList;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.CoreProtocolPNames;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.PopupWindow;

import com.listingproduct.stoplisting.Application;
import com.listingproduct.stoplisting.LiveFinderResultActivity;
import com.listingproduct.stoplisting.R;
import com.listingproduct.stoplisting.data.Plan;
import com.listingproduct.stoplisting.global.GlobalFunc;

//AsyncTask<Params, Progress, Result>
public class GetPlanListTask extends AsyncTask<String, Void, String> {

	private Context mContext;
	private Application mApplication;
	private GetPlanListTaskCallback mCallback;
	private String Tag = "GetPlanListTask";

	public GetPlanListTask(Context context, GetPlanListTaskCallback callback) {
		mContext = context;
		mApplication = (Application) mContext.getApplicationContext();
		mCallback = callback;
	}

	@Override
	protected void onPreExecute() {
		super.onPreExecute();
	}

	@Override
	protected String doInBackground(String... urls) {

		// This method for HttpConnection
		BufferedReader bufferedReader = null;
		try {
			HttpClient client = new DefaultHttpClient();
			client.getParams().setParameter(CoreProtocolPNames.USER_AGENT,
					"android");
			HttpGet request = new HttpGet();
			request.setHeader("Content-Type", "text/plain; charset=utf-8");
			request.setURI(new URI(urls[0]));
			HttpResponse response = client.execute(request);
			bufferedReader = new BufferedReader(new InputStreamReader(response
					.getEntity().getContent()));

			StringBuffer stringBuffer = new StringBuffer("");
			String line = "";

			String NL = System.getProperty("line.separator");
			while ((line = bufferedReader.readLine()) != null) {
				stringBuffer.append(line + NL);
			}
			bufferedReader.close();
			String page = stringBuffer.toString();
			return page;
		} catch (URISyntaxException | IOException e) {
			GlobalFunc.viewLog(Tag, "Error occured in reading the plan data.",
					true);
			GlobalFunc.viewLog(Tag, e.toString(), true);
			return "";
		} finally {
			if (bufferedReader != null) {
				try {
					bufferedReader.close();
				} catch (IOException e) {
					GlobalFunc.viewLog(Tag, e.toString(), true);
				}
			}
		}
	}

	@Override
	protected void onPostExecute(String result) {

		boolean bTaskResult = false;
		String strErrorMsg = "";

		if (result != null && !result.isEmpty()) {
			try {

				/* new code */
				JSONArray plansJson = new JSONArray(result);
				int nPlans = 0;
				if (plansJson != null)
					nPlans = plansJson.length();
				ArrayList<Plan> planLists = new ArrayList<Plan>();

				if (nPlans > 0) {
					// there is some plan data
					for (int i = 0; i < nPlans; i++) {
						JSONObject currentPlan = plansJson.getJSONObject(i);
						if (currentPlan != null) {
							// Plan Id
							String strPlanId = currentPlan.getString("id");
							int planId = Integer.parseInt(strPlanId);

							// Plan title
							String planTitle = currentPlan.getString("title");

							// Plan price
							String strPlanPrice = currentPlan
									.getString("price");
							int planPrice = Integer.parseInt(strPlanPrice);

							String strPlanListingCnt = currentPlan
									.getString("count");
							int planListingCnt = Integer
									.parseInt(strPlanListingCnt);

							Plan newPlan = new Plan(planId, planTitle,
									planPrice, planListingCnt);

							planLists.add(newPlan);
						}
					}

					mApplication.setPlanList(planLists);
					bTaskResult = true;
				} else {
					bTaskResult = false;
					strErrorMsg = "There is no Plans data.";
				}
			} catch (JSONException e) {
				bTaskResult = false;
				strErrorMsg = e.getMessage();
				e.printStackTrace();
			}
		} else {

			bTaskResult = false;
			strErrorMsg = "Cannot read Plans data from server. Please try again.";
			GlobalFunc.viewLog(Tag, strErrorMsg, true);
		}

		if (mCallback != null) {
			mCallback.onResult(bTaskResult, strErrorMsg);
		}
	}

	@Override
	protected void onCancelled() {
		super.onCancelled();
	}
}
