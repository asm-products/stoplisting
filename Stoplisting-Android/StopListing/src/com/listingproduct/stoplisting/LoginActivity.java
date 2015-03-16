package com.listingproduct.stoplisting;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.net.URI;
import java.net.URISyntaxException;
import java.net.URLEncoder;
import java.util.ArrayList;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.CoreProtocolPNames;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.listingproduct.stoplisting.data.StopListingItem;
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
import android.text.TextUtils;
import android.view.KeyEvent;
import android.view.View;
import android.view.Window;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.Toast;

public class LoginActivity extends Activity {

	EditText emailEdt;
	EditText passwdEdt;
	CheckBox saveInfoCb;

	// login and user info
	boolean saveInfo;
	String email;
	String password;

	private boolean isRunning;

	private final int REG_USER_RESULT = 100;

	LoginTask mLoginTask;
	String Tag = "stLoginActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.login_screen);

		emailEdt = (EditText) findViewById(R.id.emailEdt);
		passwdEdt = (EditText) findViewById(R.id.passwdEdt);
		saveInfoCb = (CheckBox) findViewById(R.id.saveInfoCb);

		// retrieve login info
		retrieveLoginInfo();
		saveInfoCb.setChecked(saveInfo);

		if (saveInfo) {
			emailEdt.setText(email);
			passwdEdt.setText(password);
		}

		// login button
		Button loginBtn = (Button) findViewById(R.id.loginBtn);
		loginBtn.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				// success login and goto next page
				// ((Application)getApplication()).setUserId(29);
				// startActivity(new Intent(LoginActivity.this,
				// MainActivity.class));
				// finish();
				loginSever();
			}
		});

		// forgot password button
		Button forgotBtn = (Button) findViewById(R.id.forgotBtn);
		forgotBtn.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				// go to forgot password page
				startActivity(new Intent(LoginActivity.this,
						ForgotPasswdActivity.class));
			}
		});

		// register button
		Button registerBtn = (Button) findViewById(R.id.registerBtn);
		registerBtn.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				// go to register page
				startActivityForResult(new Intent(LoginActivity.this,
						RegisterUserActivity.class), REG_USER_RESULT);
			}
		});

		if (saveInfo && !email.isEmpty() && !password.isEmpty()) {
			loginSever();
		}
	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		super.onActivityResult(requestCode, resultCode, data);
		if (resultCode == RESULT_OK && data != null) {
			if (requestCode == REG_USER_RESULT) {
				// User Register Success
				email = data.getStringExtra("email");
				password = data.getStringExtra("password");
				emailEdt.setText(email);
				passwdEdt.setText(password);
			}
		}
	}

	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		if (keyCode == KeyEvent.KEYCODE_BACK) {
			this.isRunning = false;
			finish();
			return true;
		}
		return super.onKeyDown(keyCode, event);
	}

	// Show Toast
	private void showToast(String msg) {
		Toast error = Toast.makeText(this, msg, Toast.LENGTH_LONG);
		error.show();
	}

	private void loginSever() {

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
		email = emailEdt.getText().toString();
		password = passwdEdt.getText().toString();
		saveInfo = saveInfoCb.isChecked();

		// 1. check email
		if (email.trim().isEmpty()) {
			showToast("Please Input your Email.");
			return;
		}

		// 2. check passsword
		if (password.trim().isEmpty()) {
			showToast("Please Input your Password.");
			return;
		}

		if (!isValidEmail(email)) {
			showToast("Email is invalid.");
			return;
		}

		// save login info
		saveLoginInfo();

		((Application) getApplication()).setEmail(email);
		((Application) getApplication()).setPw(password);

		if (mLoginTask != null) {
			mLoginTask.cancel(true);
			mLoginTask = null;
		}

		try {
			password = URLEncoder.encode(password, "UTF-8");
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		// start login task
		String strLoginUrl = String.format(GlobalDefine.SiteURLs.loginURL,
				email, password);
		mLoginTask = new LoginTask(this);
		mLoginTask.execute(strLoginUrl);
	}

	public final static boolean isValidEmail(CharSequence target) {
		if (TextUtils.isEmpty(target)) {
			return false;
		} else {
			return android.util.Patterns.EMAIL_ADDRESS.matcher(target)
					.matches();
		}
	}

	// http://stoplisting.com/api/?login&email=baixiaoyan791002@gmail.com&password=ping791002
	// http://stoplisting.com/api/?login&email=kaleem.khan287@gmail.com&password=kaleem

	// retrieve user login info
	private void retrieveLoginInfo() {
		SharedPreferences pref = getSharedPreferences("UserInfo", MODE_PRIVATE);
		saveInfo = pref.getBoolean("saveInfo", false);
		email = pref.getString("email", "");
		password = pref.getString("password", "");
	}

	// save user login info
	private void saveLoginInfo() {
		SharedPreferences pref = getSharedPreferences("UserInfo", MODE_PRIVATE);

		SharedPreferences.Editor editor = pref.edit();
		editor.putBoolean("saveInfo", saveInfo);
		editor.commit();

		// user want to save login state
		if (saveInfo) {
			editor.putString("email", email);
			editor.putString("password", password);
			editor.commit();
		}
	}

	// AsyncTask<Params,Progress,Result>
	private class LoginTask extends AsyncTask<String, Void, String> {

		private ProgressDialog mDlg;
		private Context mContext;

		public LoginTask(Context context) {
			mContext = context;
		}

		@Override
		protected void onPreExecute() {
			mDlg = new ProgressDialog(mContext);
			mDlg.setProgressStyle(ProgressDialog.STYLE_SPINNER);
			mDlg.setTitle("Please wait.");
			mDlg.setMessage("Login to Server...");
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
				HttpGet request = new HttpGet();
				request.setHeader("Content-Type", "text/plain; charset=utf-8");
				request.setURI(new URI(urls[0]));

				// Set Timeout Parameter
				HttpParams httpPrameters = new BasicHttpParams();
				HttpConnectionParams.setConnectionTimeout(httpPrameters, 10000);
				HttpConnectionParams.setSoTimeout(httpPrameters, 10000);
				request.setParams(httpPrameters);

				HttpClient client = new DefaultHttpClient();
				client.getParams().setParameter(CoreProtocolPNames.USER_AGENT,
						"android");

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
			boolean loginSuccess = false;
			String errorMsg = "";

			if (result != null && !result.isEmpty()) {
				try {
					result = result.trim();
					// JSONArray loginJson = new JSONArray(result);
					// JSONObject loginResult = loginJson.getJSONObject(0);
					JSONObject loginResult = new JSONObject(result);

					if (loginResult != null) {
						// Login status
						String strLoginStatus = loginResult.getString("status");
						int loginStatus = Integer.parseInt(strLoginStatus);

						if (loginStatus == 200) {

							// success login and get information
							String strUserName = loginResult
									.getString("username");

							String strUserId = loginResult.getString("user_id");
							int userId = Integer.parseInt(strUserId);

							String strUserPlan = loginResult
									.getString("current_plan");
							int userPlan = Integer.parseInt(strUserPlan);

							String strAuthTokenStatus = loginResult
									.getString("auth_token");

							Application appGlobal = (Application) getApplication();

							// set user information
							appGlobal.setUserName(strUserName);
							appGlobal.setUserId(userId);
							appGlobal.setUserPlan(userPlan);
							appGlobal.setTokenStatus(strAuthTokenStatus
									.equals("1"));

							// set listing information
							if (loginResult.has("listings_remaining"))
								appGlobal.mListingsRemaining = Integer
										.parseInt(loginResult
												.getString("listings_remaining"));
							if (loginResult.has("queue"))
								appGlobal.mQueue = Integer.parseInt(loginResult
										.getString("queue"));
							if (loginResult.has("week_total"))
								appGlobal.mWeekTotal = Integer
										.parseInt(loginResult
												.getString("week_total"));

							if (loginResult.has("week_total"))
								appGlobal.mWeekTotal = Integer
										.parseInt(loginResult
												.getString("week_total"));
							if (loginResult.has("need_more"))
								appGlobal.mNeedMore = Integer
										.parseInt(loginResult
												.getString("need_more"));
							if (loginResult.has("ready_publish"))
								appGlobal.mReadyPublish = Integer
										.parseInt(loginResult
												.getString("ready_publish"));
							if (loginResult.has("image_path"))
								appGlobal.mImagePath = loginResult
										.getString("image_path");

							if (loginResult.has("recent_uploads")) {
								ArrayList<StopListingItem> recentItems = appGlobal.mRecentItems;
								recentItems.clear();
								JSONArray recentDatas = loginResult
										.getJSONArray("recent_uploads");
								int nRecentItems = recentDatas.length();
								if (nRecentItems > 0) {
									for (int i = 0; i < nRecentItems; i++) {
										JSONObject curItem = recentDatas
												.getJSONObject(i);
										StopListingItem newItem = new StopListingItem();
										if (curItem.has("ui_image"))
											newItem.setPhotoUrl(curItem
													.getString("ui_image"));
										if (curItem.has("ui_id"))
											newItem.setUiId(curItem
													.getString("ui_id"));
										if (curItem.has("ui_date"))
											newItem.setDate(curItem
													.getString("ui_date"));
										recentItems.add(newItem);
									}
								}
							}

							JSONObject jsonListings = null;
							if (loginResult.has("listings"))
								jsonListings = loginResult
										.getJSONObject("listings");

							if (jsonListings != null) {
								ArrayList<StopListingItem> pubedItems = appGlobal.mPublishedItems;
								ArrayList<StopListingItem> unPubedItems = appGlobal.mUnPublishedItems;
								ArrayList<StopListingItem> rejectedItems = appGlobal.mRejectedItems;
								pubedItems.clear();
								unPubedItems.clear();
								rejectedItems.clear();

								JSONArray jsonPublishedArray = null;
								JSONArray jsonUnPublishedArray = null;
								JSONArray jsonRejectedArray = null;

								if (jsonListings.has("published"))
									jsonPublishedArray = jsonListings
											.getJSONArray("published");
								if (jsonListings.has("unpublished"))
									jsonUnPublishedArray = jsonListings
											.getJSONArray("unpublished");
								if (jsonListings.has("rejected"))
									jsonRejectedArray = jsonListings
											.getJSONArray("rejected");

								// parsing Published Items
								if (jsonPublishedArray != null) {
									int nItems = jsonPublishedArray.length();
									for (int i = 0; i < nItems; i++) {
										JSONObject curItem = jsonPublishedArray
												.getJSONObject(i);
										String strTitle = curItem
												.getString("title");
										float fPrice = 0;
										String strPrice = curItem
												.getString("price");
										if (!strPrice.isEmpty())
											fPrice = Float.parseFloat(strPrice);
										String strImagePath = curItem
												.getString("ui_image");
										String strUiId = curItem
												.getString("ui_id");
										StopListingItem newItem = new StopListingItem();
										newItem.setTitle(strTitle);
										newItem.setPrice(fPrice);
										newItem.setPhotoUrl(strImagePath);
										newItem.setUiId(strUiId);
										pubedItems.add(newItem);
									}
								}

								// parsing UnPublished Items
								if (jsonUnPublishedArray != null) {
									int nItems = jsonUnPublishedArray.length();
									for (int i = 0; i < nItems; i++) {
										JSONObject curItem = jsonUnPublishedArray
												.getJSONObject(i);
										String strTitle = curItem
												.getString("title");
										float fPrice = 0;
										String strPrice = curItem
												.getString("price");
										if (!strPrice.isEmpty())
											fPrice = Float.parseFloat(strPrice);
										String strImagePath = curItem
												.getString("ui_image");
										String strUiId = curItem
												.getString("ui_id");
										StopListingItem newItem = new StopListingItem();
										newItem.setTitle(strTitle);
										newItem.setPrice(fPrice);
										newItem.setPhotoUrl(strImagePath);
										newItem.setUiId(strUiId);
										unPubedItems.add(newItem);
									}
								}

								// parsing Rejected Items
								if (jsonRejectedArray != null) {
									int nItems = jsonRejectedArray.length();
									for (int i = 0; i < nItems; i++) {

										JSONObject curItem = jsonRejectedArray
												.getJSONObject(i);
										String strTitle = curItem
												.getString("title");
										float fPrice = 0;
										String strPrice = curItem
												.getString("price");
										if (!strPrice.isEmpty())
											fPrice = Float.parseFloat(strPrice);
										String strImagePath = curItem
												.getString("ui_image");
										String strUiId = curItem
												.getString("ui_id");
										StopListingItem newItem = new StopListingItem();
										newItem.setTitle(strTitle);
										newItem.setPrice(fPrice);
										newItem.setPhotoUrl(strImagePath);
										newItem.setUiId(strUiId);
										rejectedItems.add(newItem);
									}
								}
							}

							loginSuccess = true;
						} else if (loginStatus == 503) {
							// Invalide user info
							loginSuccess = false;
							errorMsg = "Login Failure! \n Please confirm user information again.";
						} else {
							// Unknown error
							loginSuccess = false;
							errorMsg = "Login Failure! \n Please try again.";
						}
					} else {
						// Unknown error
						loginSuccess = false;
						errorMsg = "Login Failure! \n There is no login result. Please try again.";
					}
				} catch (JSONException e) {
					// Json Parsing error
					loginSuccess = false;
					// errorMsg = "Login Failure! \n Please try again.";
					errorMsg = e.getMessage();
				}
			} else {
				// Network error
				loginSuccess = false;
				errorMsg = "Login Failure! \nPlease confirm your network connection with server.";
			}

			mDlg.dismiss();
			if (loginSuccess) {
				// success login and goto next page
				startActivity(new Intent(LoginActivity.this, MainActivity.class));
				finish();
			} else {
				// Failed login
				GlobalFunc.viewLog(Tag, "Cannot login to server.", true);
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
