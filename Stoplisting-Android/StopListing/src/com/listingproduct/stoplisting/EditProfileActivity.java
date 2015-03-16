package com.listingproduct.stoplisting;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.client.utils.URLEncodedUtils;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONException;
import org.json.JSONObject;

import com.listingproduct.stoplisting.global.GlobalDefine;
import com.listingproduct.stoplisting.task.AnyTask;
import com.listingproduct.stoplisting.task.AnyTaskCallback;

import android.content.Context;
import android.content.SharedPreferences;
import android.graphics.Typeface;
import android.os.Bundle;
import android.view.View;
import android.view.WindowManager;
import android.view.View.OnClickListener;
import android.view.inputmethod.InputMethodManager;
import android.view.Window;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class EditProfileActivity extends MyBaseActivity implements
		OnClickListener {

	Application application;
	int userID;

	String strUsername;
	String strEmail;
	String strPasswd;

	String newUsername;
	String newEmail;
	String newPasswd1;
	String newPasswd2;

	boolean changedUsername;
	boolean changedEmail;
	boolean changedPasswd;

	EditText userNameEt;
	EditText emailEt;
	EditText passwd1Et;
	EditText passwd2Et;

	String Tag = "stEditProfileActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.edit_profile_screen);

		application = (Application) getApplication();
		userID = application.getUserId();

		Typeface font = application.getFont();

		Button backBtn = (Button) findViewById(R.id.backBtn);
		backBtn.setTypeface(font);
		backBtn.setOnClickListener(this);

		userNameEt = (EditText) findViewById(R.id.userNameEt);
		emailEt = (EditText) findViewById(R.id.emailEt);
		passwd1Et = (EditText) findViewById(R.id.passwd1Et);
		passwd2Et = (EditText) findViewById(R.id.passwd2Et);

		strUsername = application.getUserName();
		strEmail = application.getEmail();
		strPasswd = application.getPw();
		userNameEt.setText(strUsername);
		emailEt.setText(strEmail);

		Button updateBtn = (Button) findViewById(R.id.updateBtn);
		updateBtn.setOnClickListener(this);
	}

	@Override
	public void onClick(View v) {
		switch (v.getId()) {
		case R.id.backBtn:
			finish();
			break;
		case R.id.updateBtn:
			updateMyProfile();
			break;
		}
	}

	private void removeKeyboard(EditText et) {
		if (et != null) {
			InputMethodManager imm= (InputMethodManager)getSystemService(Context.INPUT_METHOD_SERVICE);
			imm.hideSoftInputFromWindow(et.getWindowToken(), 0);
		}
	}
	
	private void updateMyProfile() {

		// hide keyboard
		if (emailEt.isFocused())
			removeKeyboard(emailEt);
		if (userNameEt.isFocused())
			removeKeyboard(userNameEt);
		if (passwd1Et.isFocused())
			removeKeyboard(passwd1Et);
		if (passwd2Et.isFocused())
			removeKeyboard(passwd2Et);
				
		changedUsername = false;
		changedEmail = false;
		changedPasswd = false;

		newEmail = emailEt.getText().toString();
		newUsername = userNameEt.getText().toString();
		newPasswd1 = passwd1Et.getText().toString();
		newPasswd2 = passwd2Et.getText().toString();

		String field = "";
		String value = "";

		if (!newEmail.equals(strEmail)) {
			// check new email
			if (newEmail.isEmpty() || !LoginActivity.isValidEmail(newEmail)) {
				showToast("Email is invalid.", Toast.LENGTH_LONG);
				return;
			}
			field = "email";
			value = newEmail;
			changedEmail = true;
		}

		if (!newUsername.equals(strUsername)) {
			if (newUsername.isEmpty()) {
				showToast("Username is invalid.", Toast.LENGTH_LONG);
				return;
			}

			if (!field.isEmpty())
				field += ",";
			if (!value.isEmpty())
				value += "-_:|:_-";

			field += "username";
			value += newUsername;
			changedUsername = true;
		}

		if (!newPasswd1.isEmpty() || !newPasswd2.isEmpty()) {
			if (!newPasswd1.equals(newPasswd2)) {
				showToast("Confirm password is not equal with password.",
						Toast.LENGTH_LONG);
				return;
			}

			if (!field.isEmpty())
				field += ",";
			if (!value.isEmpty())
				value += "-_:|:_-";

			field += "password";
			value += newPasswd1;

			changedPasswd = true;
		}

		// make parameter
		List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(3);
		nameValuePairs.add(new BasicNameValuePair("user_id", String
				.valueOf(userID)));
		nameValuePairs.add(new BasicNameValuePair("field", field));
		nameValuePairs.add(new BasicNameValuePair("value", value));
		String paramsString = URLEncodedUtils.format(nameValuePairs, "UTF-8");
		
		String strUrl = GlobalDefine.SiteURLs.setMultiAction + paramsString;

		AnyTask profileUpdateTask = new AnyTask(this,
				profileUpdateTaskCallBack, "Please wait",
				"Your request is now processing...");
		profileUpdateTask.execute(strUrl);

	}

	AnyTaskCallback profileUpdateTaskCallBack = new AnyTaskCallback() {

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
						strResultMsg = "Successfully updated your profile.";
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
			if (taskSuccess) {
				if (changedUsername) {
					application.setUserName(newUsername);
				}

				if (changedEmail) {
					application.setEmail(newEmail);
				}

				if (changedPasswd) {
					application.setPw(newPasswd1);
				}

				// check Auto Login status
				SharedPreferences pref = getSharedPreferences("UserInfo",
						MODE_PRIVATE);
				boolean saveInfo = pref.getBoolean("saveInfo", false);

				// user want to save login state
				if (saveInfo) {
					SharedPreferences.Editor editor = pref.edit();
					if (changedEmail)
						editor.putString("email", newEmail);
					if (changedPasswd)
						editor.putString("password", newPasswd1);
					editor.commit();
				}
			}
		}
	};

	private void showToast(String msg, int duration) {
		if (msg == null || msg.isEmpty())
			return;
		Toast.makeText(this, msg, duration).show();
	}
}
