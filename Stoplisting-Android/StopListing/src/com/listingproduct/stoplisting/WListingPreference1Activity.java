package com.listingproduct.stoplisting;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
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
import android.graphics.Typeface;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.view.View.OnClickListener;
import android.view.inputmethod.InputMethodManager;
import android.webkit.WebSettings;
import android.webkit.WebViewClient;
import android.webkit.WebSettings.PluginState;
import android.webkit.WebView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class WListingPreference1Activity extends Activity implements
		OnClickListener {

	EditText contentEt;
	private static final String FIELD = "listing_preference";
	private static final String TITLE_MSG = "Please wait";
	private static final String RETRIEVING_MSG = "Retrieving your listing preference information...";
	private static final String UPDATING_MSG = "Updating your listing preference information...";
	
	int userId;
	String strEmail;
	String content = "";
	String newContent = "";
	
	Button submitBtn;
	
	@SuppressWarnings("deprecation")
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.wlisting_preference_screen1);

		strEmail = ((Application) getApplication()).getEmail();
		userId = ((Application) getApplication()).getUserId();

		Button backBut = (Button) findViewById(R.id.backBut);
		backBut.setTypeface(Typeface.createFromAsset(getAssets(),
				"fontawesome-webfont.ttf"));
		backBut.setOnClickListener(this);

		contentEt = (EditText) findViewById(R.id.contentEt);

		submitBtn = (Button) findViewById(R.id.submitBtn);
		submitBtn.setOnClickListener(this);

		// Start a Task for retrieving the template information
		List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
		nameValuePairs.add(new BasicNameValuePair("user_id", String
				.valueOf(userId)));
		nameValuePairs.add(new BasicNameValuePair("field", FIELD));
		String paramsString = URLEncodedUtils.format(nameValuePairs, "UTF-8");
		String strUrl = GlobalDefine.SiteURLs.getAction + paramsString;

		AnyTask retriveInfoTask = new AnyTask(this, retrieveInfoTaskCallBack,
				TITLE_MSG,
				RETRIEVING_MSG);

		retriveInfoTask.execute(strUrl);
	}

	AnyTaskCallback retrieveInfoTaskCallBack = new AnyTaskCallback() {

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

						JSONObject dataJson = userDataJsons.getJSONObject(0);
						if (dataJson != null) {
							if (dataJson.has(FIELD)) {
								content = dataJson.getString(FIELD);
								contentEt.setText(content);
							}
						}
					}
				} catch (JSONException e) {
					e.printStackTrace();
				}
			}
		}
	};

	@Override
	public void onClick(View view) {
		switch (view.getId()) {
		case R.id.backBut:
			finish();
			break;
		case R.id.submitBtn:
			updateContent();
			break;
		default:
			break;
		}
	}

	private void removeKeyboard(EditText et) {
		if (et != null) {
			InputMethodManager imm = (InputMethodManager) getSystemService(Context.INPUT_METHOD_SERVICE);
			imm.hideSoftInputFromWindow(et.getWindowToken(), 0);
		}
	}

	private void updateContent() {

		if (contentEt.isFocused())
			removeKeyboard(contentEt);

		newContent = contentEt.getText().toString();
		if (newContent.equals(content))
			return;

		submitBtn.setText(R.string.title_updating_btn);
		submitBtn.setEnabled(false);
		// Start a Task for retrieving the template information
		List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(3);
		nameValuePairs.add(new BasicNameValuePair("user_id", String
				.valueOf(userId)));
		nameValuePairs.add(new BasicNameValuePair("field", FIELD));
		nameValuePairs.add(new BasicNameValuePair("value", newContent));
		String paramsString = URLEncodedUtils.format(nameValuePairs, "UTF-8");
		String strUrl = GlobalDefine.SiteURLs.setAction + paramsString;

		AnyTask updateInfoTask = new AnyTask(this, updateInfoTaskCallBack,
				TITLE_MSG, UPDATING_MSG);

		updateInfoTask.execute(strUrl);
	}

	AnyTaskCallback updateInfoTaskCallBack = new AnyTaskCallback() {

		@Override
		public void onResult(boolean success, String result) {

			String strMessage = "";
			if (success) {
				result = result.trim();
				try {
					JSONObject jsonResult = new JSONObject(result);
					String strStatus = jsonResult.getString("status");
					if (strStatus.equals("200")) {
						strMessage = "Successfully updated";
					} else {
						strMessage = "Updated Failure";
					}
				} catch (JSONException e) {
					strMessage = e.getMessage();
					e.printStackTrace();
				}
			} else {
				strMessage = result;
			}
			
			if (!strMessage.isEmpty()) {
				Toast.makeText(WListingPreference1Activity.this, strMessage,
						Toast.LENGTH_LONG).show();
			}
			
			submitBtn.setText(R.string.title_submit_btn);
			submitBtn.setEnabled(true);
		}
	};
}
