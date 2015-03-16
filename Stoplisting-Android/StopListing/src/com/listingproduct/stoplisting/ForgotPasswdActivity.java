package com.listingproduct.stoplisting;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.net.URI;
import java.net.URISyntaxException;
import java.net.URLEncoder;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.CoreProtocolPNames;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.listingproduct.stoplisting.global.GlobalDefine;
import com.listingproduct.stoplisting.global.GlobalFunc;
import com.listingproduct.stoplisting.utilities.NetStateUtilities;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.view.Window;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.Toast;

public class ForgotPasswdActivity extends Activity {

	EditText emailEdt;

	PasswdRequestTask mPasswdTask;
	String Tag = "stForgotPasswdActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.forgot_pass_screen);

		emailEdt = (EditText) findViewById(R.id.emailEdt);

		Button sendBtn = (Button) findViewById(R.id.btnSend);
		sendBtn.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				sendPasswdRequest();
			}
		});
	}

	// Show Toast
	private void showToast(String msg) {
		Toast error = Toast.makeText(this, msg, Toast.LENGTH_LONG);
		error.show();
	}

	private void sendPasswdRequest() {

		// Network state check
		if (!NetStateUtilities.hasConnection(this)) {

			AlertDialog.Builder builder = new AlertDialog.Builder(this);

			// set property
			builder.setTitle("Notice")
					.setMessage("Cannot login sever. No network connection.")
					.setPositiveButton("Ok",
							new DialogInterface.OnClickListener() {
								public void onClick(DialogInterface dialog,
										int whichButton) {
									dialog.dismiss();
								}
							});

			AlertDialog dialog = builder.create();
			dialog.show(); // Show AlertDialog
			return;
		}

		// get login info
		String email = emailEdt.getText().toString();

		// check email
		if (email.trim().isEmpty()) {
			showToast("Please Input your Email.");
			return;
		}

		if (mPasswdTask != null) {
			mPasswdTask.cancel(true);
			mPasswdTask = null;
		}

		try {
			email = URLEncoder.encode(email, "UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		
		// start Password request task
		String strPasswdReqUrl = String.format(GlobalDefine.SiteURLs.passwdReqURL, email);
		mPasswdTask = new PasswdRequestTask(this);
		mPasswdTask.execute(strPasswdReqUrl);
	}

	// AsyncTask<Params,Progress,Result>
	private class PasswdRequestTask extends AsyncTask<String, Void, String> {

		private ProgressDialog mDlg;
		private Context mContext;

		public PasswdRequestTask(Context context) {
			mContext = context;
		}

		@Override
		protected void onPreExecute() {
			mDlg = new ProgressDialog(mContext);
			mDlg.setProgressStyle(ProgressDialog.STYLE_SPINNER);
			mDlg.setTitle("Please wait.");
			mDlg.setMessage("Request to Server...");
			mDlg.setCanceledOnTouchOutside(false);
			mDlg.setCancelable(false);
			mDlg.show();
			super.onPreExecute();
		}

		@Override
		protected String doInBackground(String... urls) {
			return "";
			
			/*// This method for HttpConnection
			BufferedReader bufferedReader = null;
			try {
				HttpClient client = new DefaultHttpClient();
				client.getParams().setParameter(CoreProtocolPNames.USER_AGENT,
						"android");
				HttpGet request = new HttpGet();
				request.setHeader("Content-Type", "text/plain; charset=utf-8");
				request.setURI(new URI(urls[0]));
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
				return page;
			} catch (URISyntaxException | IOException e) {
				GlobalFunc.viewLog(Tag,
						"Error occured in reading the plan data.", true);
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
			}*/
		}

		@Override
		protected void onPostExecute(String result) {
			boolean requestSuccess = false;
			String errorMsg = "";

			if (result != null && !result.isEmpty()) {
				try {
					JSONArray loginJson = new JSONArray(result);
					JSONObject loginResult = loginJson.getJSONObject(0);

					if (loginResult != null) {
						// Login status
						String strLoginStatus = loginResult.getString("status");
						int loginStatus = Integer.parseInt(strLoginStatus);

						if (loginStatus == 200) {
							
							// success request
							requestSuccess = true;
						} else if (loginStatus == 503) {
							
							// Invalide user info
							requestSuccess = false;
							errorMsg = "Request Failure! \n There is no user for your mail.";
						} else {
							// Unknown error
							requestSuccess = false;
							errorMsg = "Request Failure! \n Please try again.";
						}
					} else {
						// Unknown error
						requestSuccess = false;
						errorMsg = "Request Failure! \n There is no request result. Please try again.";
					}
				} catch (JSONException e) {
					// Json Parsing error
					requestSuccess = false;
					errorMsg = "Request Failure! \n Please try again.";
				}
			} else {
				// Network error
				requestSuccess = false;
				errorMsg = "Request Failure! \nPlease confirm your network connection with server.";
			}

			mDlg.dismiss();
			if (requestSuccess) {
				// success request
				showToast("Successfully send your request. Please check your mail box.");
			} else {
				// Failed login
				GlobalFunc.viewLog(Tag, "Password request Failure!", true);
				mDlg.dismiss();
				AlertDialog.Builder builder = new AlertDialog.Builder(mContext); 
				// set property
				builder.setTitle("Warning")
						.setMessage(errorMsg)
						.setPositiveButton("Ok",
								new DialogInterface.OnClickListener() {
									public void onClick(DialogInterface dialog,
											int whichButton) {
										dialog.dismiss();
									}
								});

				AlertDialog dialog = builder.create();
				dialog.show(); // Show AlertDialog
			}
		}

		@Override
		protected void onCancelled() {
			mDlg.dismiss();
			super.onCancelled();
		}
	}
}
