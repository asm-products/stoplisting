package com.listingproduct.stoplisting;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.client.UserTokenHandler;
import org.apache.http.client.utils.URLEncodedUtils;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.listingproduct.stoplisting.global.GlobalDefine;
import com.listingproduct.stoplisting.task.AnyTask;
import com.listingproduct.stoplisting.task.AnyTaskCallback;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Typeface;
import android.os.Bundle;
import android.view.View;
import android.view.WindowManager;
import android.view.View.OnClickListener;
import android.view.Window;
import android.view.inputmethod.InputMethodManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class AccountSyncActivity extends MyBaseActivity implements
		OnClickListener {

	Application application;
	int userID;

	EditText mPaypalIDEt;
	String mPaypalID = "";

	String mAuthSessionID;
	String mAuthSiteUrl;

	private final static int AUTH_TOKEN = 100;

	String Tag = "stMoreInfoActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.account_sync_screen);

		application = (Application) getApplication();
		userID = application.getUserId();

		Typeface font = application.getFont();

		Button backBtn = (Button) findViewById(R.id.backBtn);
		backBtn.setTypeface(font);
		backBtn.setOnClickListener(this);

		mPaypalIDEt = (EditText) findViewById(R.id.paypalIDEt);

		Button saveBtn = (Button) findViewById(R.id.saveBtn);
		saveBtn.setOnClickListener(this);

		Button setEbayTokenBtn = (Button) findViewById(R.id.setEbayTokenBtn);
		setEbayTokenBtn.setOnClickListener(this);

		// Start a Task for retrieving the paypal information
		List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
		nameValuePairs.add(new BasicNameValuePair("user_id", String
				.valueOf(userID)));
		nameValuePairs.add(new BasicNameValuePair("field", "paypal"));
		String paramsString = URLEncodedUtils.format(nameValuePairs, "UTF-8");
		String strUrl = GlobalDefine.SiteURLs.getAction + paramsString;
		AnyTask retrivePPInfoTask = new AnyTask(this,
				retrievePayPalInfoTaskCallBack, "Please wait",
				"Retrieving your profile information...");
		retrivePPInfoTask.execute(strUrl);

	}

	AnyTaskCallback retrievePayPalInfoTaskCallBack = new AnyTaskCallback() {

		@Override
		public void onResult(boolean success, String result) {

			if (success) {
				result = result.trim();
				try {
					JSONObject jsonResult = new JSONObject(result);
					String strStatus = jsonResult.getString("status");
					if (strStatus.equals("200")) {
						JSONArray userDataJsons = jsonResult
								.getJSONArray("user_data");
						JSONObject userData = userDataJsons.getJSONObject(0);
						String strPayPalId = userData.getString("paypal_id");
						mPaypalIDEt.setText(strPayPalId);
						mPaypalID = strPayPalId;
					}
				} catch (JSONException e) {
					e.printStackTrace();
				}
			}
		}
	};

	@Override
	public void onClick(View v) {
		switch (v.getId()) {
		case R.id.backBtn:
			finish();
			break;
		case R.id.saveBtn:
			updateProfile();
			break;
		case R.id.setEbayTokenBtn:
			startEbayTokenSetting();
			break;
		}
	}

	private void updateProfile() {
		// hide keyboard
		//getWindow().setSoftInputMode(
		//		WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_HIDDEN);
		InputMethodManager imm= (InputMethodManager)getSystemService(Context.INPUT_METHOD_SERVICE);
		imm.hideSoftInputFromWindow(mPaypalIDEt.getWindowToken(), 0);
		
		String newPayPalId = mPaypalIDEt.getText().toString();
		if (newPayPalId.equals(mPaypalID)) {
			return;
		}

		// Start a Task for retrieving the paypal information
		List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(3);
		nameValuePairs.add(new BasicNameValuePair("user_id", String
				.valueOf(userID)));
		nameValuePairs.add(new BasicNameValuePair("field", "paypal"));
		nameValuePairs.add(new BasicNameValuePair("value", newPayPalId));
		String paramsString = URLEncodedUtils.format(nameValuePairs, "UTF-8");
		String strUrl = GlobalDefine.SiteURLs.setAction + paramsString;
		AnyTask savePPInfoTask = new AnyTask(this, savePayPalInfoTaskCallBack,
				"Please wait", "Saving your profile information...");
		savePPInfoTask.execute(strUrl);
	}

	AnyTaskCallback savePayPalInfoTaskCallBack = new AnyTaskCallback() {

		@Override
		public void onResult(boolean success, String result) {

			boolean taskSuccess = false;
			String strResultMsg = "";

			if (!success) {
				if (result != null && !result.isEmpty()) {
					strResultMsg = result;
				} else {
					strResultMsg = "Action Failure. Please try again.";
				}
			} else {
				result = result.trim();
				try {
					JSONObject jsonResult = new JSONObject(result);
					String strLoginStatus = jsonResult.getString("status");
					if (strLoginStatus.equals("200")) {
						taskSuccess = true;
						strResultMsg = "Successfully save your profile.";
					} else if (strLoginStatus.equals("503")) {
						strResultMsg = jsonResult.getString("process");
					} else {
						strResultMsg = "Action Failure. Unknown Error.";
					}
				} catch (JSONException e) {
					strResultMsg = e.getMessage();
					e.printStackTrace();
				}
			}

			showToast(strResultMsg, Toast.LENGTH_LONG);
		}
	};

	private void startEbayTokenSetting() {

		mAuthSessionID = "";
		mAuthSiteUrl = "";

		// Start a Task for getting Auth Token Session ID
		String strUrl = GlobalDefine.SiteURLs.getSesstionIdForAuthToken;
		AnyTask savePPInfoTask = new AnyTask(this, getSesstionIDTaskCallBack,
				"Please wait", "Getting the information for Ebay Auth Token...");
		savePPInfoTask.execute(strUrl);
	}

	AnyTaskCallback getSesstionIDTaskCallBack = new AnyTaskCallback() {

		@Override
		public void onResult(boolean success, String result) {

			boolean taskSuccess = false;
			String strResultMsg = "";

			if (!success) {
				if (result != null && !result.isEmpty()) {
					strResultMsg = result;
				} else {
					strResultMsg = "Action Failure. Please try again.";
				}
			} else {
				result = result.trim();
				try {
					JSONObject jsonResult = new JSONObject(result);
					String strStatus = jsonResult.getString("status");
					if (strStatus.equals("200")) {
						mAuthSessionID = jsonResult.getString("session_id");
						mAuthSiteUrl = jsonResult.getString("result_url");
						strResultMsg = "Successfully get the session id for Ebay Auth Token.";
						taskSuccess = true;
					} else if (strStatus.equals("503")) {
						strResultMsg = jsonResult.getString("process");
					} else {
						strResultMsg = "Action Failure. Unknown Error.";
					}
				} catch (JSONException e) {
					strResultMsg = e.getMessage();
					e.printStackTrace();
				}
			}

			if (!taskSuccess) {
				showToast(strResultMsg, Toast.LENGTH_LONG);
			} else {
				// Open Site
				Intent intent = new Intent(AccountSyncActivity.this,
						AuthTokenActivity.class);
				intent.putExtra("authTokenUrl", mAuthSiteUrl);
				startActivityForResult(intent, AUTH_TOKEN);
			}
		}
	};

	protected void onActivityResult(int requestCode, int resultCode,
			android.content.Intent data) {
		if (requestCode == AUTH_TOKEN) {
			String newPayPalId = mPaypalIDEt.getText().toString();
			if (mAuthSessionID.isEmpty()) {
				return;
			}

			// Start a Task for retrieving the paypal information
			List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
			nameValuePairs.add(new BasicNameValuePair("session_id",
					mAuthSessionID));
			nameValuePairs.add(new BasicNameValuePair("user_id", String
					.valueOf(userID)));
			String paramsString = URLEncodedUtils.format(nameValuePairs,
					"UTF-8");
			String strUrl = GlobalDefine.SiteURLs.setToken + paramsString;
			AnyTask setTokenTask = new AnyTask(this, setTokenTaskCallBack,
					"Please wait", "Checking for Ebay Token...");
			setTokenTask.execute(strUrl);
		}
	}

	AnyTaskCallback setTokenTaskCallBack = new AnyTaskCallback() {

		@Override
		public void onResult(boolean success, String result) {

			boolean taskSuccess = false;
			String strResultMsg = "";

			if (!success) {
				if (result != null && !result.isEmpty()) {
					strResultMsg = result;
				} else {
					strResultMsg = "Action Failure. Please try again.";
				}
			} else {
				result = result.trim();
				try {
					JSONObject jsonResult = new JSONObject(result);
					String strLoginStatus = jsonResult.getString("status");
					if (strLoginStatus.equals("200")) {
						taskSuccess = true;
						strResultMsg = "Successfully set your Token.";
						application.setTokenStatus(true);
					} else if (strLoginStatus.equals("404")) {
						strResultMsg = jsonResult.getString("error");
					} else {
						strResultMsg = "Action Failure. Unknown Error.";
					}
				} catch (JSONException e) {
					strResultMsg = e.getMessage();
					e.printStackTrace();
				}
			}
			showToast(strResultMsg, Toast.LENGTH_LONG);
		}
	};

	private void showToast(String strMsg, int duration) {
		if (strMsg == null || strMsg.isEmpty())
			return;
		Toast.makeText(this, strMsg, duration).show();
	}
}
