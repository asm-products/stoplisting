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

public class RegisterUserActivity extends Activity {

	EditText usernameEdt;
	EditText emailEdt;
	EditText passwd1Edt;
	EditText passwd2Edt;

	String userName;
	String email;
	String passwd;
	String passwdConfirm;
	
	RegRequestTask mRegisterTask;
	String Tag = "stForgotPasswdActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.register_user_screen);

		usernameEdt = (EditText) findViewById(R.id.usernameEdt);
		emailEdt = (EditText) findViewById(R.id.emailEdt);
		passwd1Edt = (EditText) findViewById(R.id.passwd1Edt);
		passwd2Edt = (EditText) findViewById(R.id.passwd2Edt);

		Button regBtn = (Button) findViewById(R.id.btnSend);
		regBtn.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				sendRegRequest();
			}
		});
	}

	// Show Toast
	private void showToast(String msg) {
		Toast error = Toast.makeText(this, msg, Toast.LENGTH_LONG);
		error.show();
	}

	private void sendRegRequest() {

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
		userName = usernameEdt.getText().toString().trim();
		email = emailEdt.getText().toString().trim();
		passwd = passwd1Edt.getText().toString().trim();
		passwdConfirm = passwd2Edt.getText().toString().trim();

		// check username
		if (userName.isEmpty()) {
			showToast("Please fill your name.");
			return;
		}

		// check email
		if (email.isEmpty()) {
			showToast("Please fill your email.");
			return;
		}

		// check password
		if (passwd.isEmpty()) {
			showToast("Please fill your password.");
			return;
		}

		// check password length
		if (passwd.length() < 5) {
			showToast("Password must be 5 characters at least.");
			return;
		}
		
		// check email
		if (passwdConfirm.isEmpty()) {
			showToast("Please fill confirm password.");
			return;
		}

		// check password consistency
		if (!passwd.equals(passwdConfirm)) {
			showToast("Confirm password is not equal with password.");
			return;
		}

		if (mRegisterTask != null) {
			mRegisterTask.cancel(true);
			mRegisterTask = null;
		}

		String encodedName = userName;
		String encodedEmail = email;
		String encodedPasswd = passwd;
		try {
			encodedName = URLEncoder.encode(encodedName, "UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		
		try {
			encodedEmail = URLEncoder.encode(encodedEmail, "UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		
		try {
			encodedPasswd = URLEncoder.encode(encodedPasswd, "UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		
		// start Password request task
		String strRegRequestUrl = String.format(
				GlobalDefine.SiteURLs.registerURL, encodedName, encodedEmail, encodedPasswd);
		mRegisterTask = new RegRequestTask(this);
		mRegisterTask.execute(strRegRequestUrl);

	}

	// AsyncTask<Params,Progress,Result>
	private class RegRequestTask extends AsyncTask<String, Void, String> {

		private ProgressDialog mDlg;
		private Context mContext;

		public RegRequestTask(Context context) {
			mContext = context;
		}

		@Override
		protected void onPreExecute() {
			mDlg = new ProgressDialog(mContext);
			mDlg.setProgressStyle(ProgressDialog.STYLE_SPINNER);
			mDlg.setTitle("Please wait.");
			mDlg.setMessage("Registering to Server...");
			mDlg.setCanceledOnTouchOutside(false);
			mDlg.setCancelable(false);
			mDlg.show();
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
			}
		}

		@Override
		protected void onPostExecute(String result) {
			boolean requestSuccess = false;
			String errorMsg = "";

			if (result != null && !result.isEmpty()) {
				try {
					JSONArray registerJson = new JSONArray(result);
					JSONObject registerResult = registerJson.getJSONObject(0);

					if (registerResult != null) {
						// Login status
						String strRegisterStatus = registerResult.getString("status");
						int loginStatus = Integer.parseInt(strRegisterStatus);

						if (loginStatus == 200) {
							// success request
							requestSuccess = true;
						} else if (loginStatus == 0) {
							// Invalide user info
							requestSuccess = false;
							errorMsg = registerResult.getString("errormsg");
						} else {
							// Unknown error
							requestSuccess = false;
							errorMsg = "Register Failure! \n Please try again.";
						}
					} else {
						// Unknown error
						requestSuccess = false;
						errorMsg = "Register Failure! \n There is no request result. Please try again.";
					}
				} catch (JSONException e) {
					// Json Parsing error
					requestSuccess = false;
					errorMsg = "Register Failure! \n Please try again.";
				}
			} else {
				// Network error
				requestSuccess = false;
				errorMsg = "Register Failure! \nPlease confirm your network connection with server.";
			}

			mDlg.dismiss();
			if (requestSuccess) {
				// success request
				showToast("Successfully registered.");
				
				// set Result
				Intent data = getIntent();
				data.putExtra("email", email);
				data.putExtra("password", passwd);
				setResult(RESULT_OK, data); 			
				finish();
			} else {
				// Failed login
				GlobalFunc.viewLog(Tag, "Cannot register user!", true);
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
