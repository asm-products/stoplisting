package com.listingproduct.stoplisting.fragment;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.client.utils.URLEncodedUtils;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONException;
import org.json.JSONObject;

import com.listingproduct.stoplisting.Application;
import com.listingproduct.stoplisting.EditProfileActivity;
import com.listingproduct.stoplisting.ListingSettingsActivity;
import com.listingproduct.stoplisting.MainActivity;
import com.listingproduct.stoplisting.AccountSyncActivity;
import com.listingproduct.stoplisting.R;
import com.listingproduct.stoplisting.global.GlobalDefine;
import com.listingproduct.stoplisting.task.AnyTask;
import com.listingproduct.stoplisting.task.AnyTaskCallback;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Typeface;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.view.WindowManager;
import android.view.inputmethod.InputMethodManager;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

public class AccountFragment extends Fragment implements OnClickListener {

	MainActivity activity;
	Application application;
	int userID;
	
	TextView userNameTv;
	TextView planTv;

	String Tag = "stAccountFragment";
	
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {

		View view = inflater.inflate(R.layout.account_screen, container, false);

		activity = (MainActivity) getActivity();
		application = (Application) activity.getApplication();
		userID = application.getUserId();

		userNameTv = (TextView) view.findViewById(R.id.userNameTv);
		planTv = (TextView) view.findViewById(R.id.planTv);
		
		// set icon font
		TextView iconUser = (TextView) view.findViewById(R.id.iconUser);
		TextView iconUpgradePlan = (TextView) view
				.findViewById(R.id.iconUpgradePlan);
		TextView iconMoreInfo = (TextView) view.findViewById(R.id.iconMoreInfo);
		TextView iconListingSetting = (TextView) view
				.findViewById(R.id.iconListingSetting);
		TextView iconDeleteAccount = (TextView) view
				.findViewById(R.id.iconDeleteAccount);
		TextView iconLogout = (TextView) view.findViewById(R.id.iconLogout);

		Typeface font = ((MainActivity) getActivity()).font;
		iconUser.setTypeface(font);
		iconUpgradePlan.setTypeface(font);
		iconMoreInfo.setTypeface(font);
		iconListingSetting.setTypeface(font);
		iconDeleteAccount.setTypeface(font);
		iconLogout.setTypeface(font);

		// get item and set listner
		View editMyProfileLL = view.findViewById(R.id.editMyProfileLL);
		editMyProfileLL.setOnClickListener(this);

		View upgradePlanLL = view.findViewById(R.id.upgradePlanLL);
		upgradePlanLL.setOnClickListener(this);

		View accountSyncLL = view.findViewById(R.id.accountSyncLL);
		accountSyncLL.setOnClickListener(this);

		View listingSettingsLL = view.findViewById(R.id.listingSettingsLL);
		listingSettingsLL.setOnClickListener(this);

		View deleteAccountLL = view.findViewById(R.id.deleteAccountLL);
		deleteAccountLL.setOnClickListener(this);

		View logoutLL = view.findViewById(R.id.logoutLL);
		logoutLL.setOnClickListener(this);

		return view;
	}

	@Override
	public void onResume() {
		super.onResume();

		// Update User Information : username and plan
		String strUserName = ((Application) getActivity().getApplication())
				.getUserName();
		userNameTv.setText(strUserName);

		String strPlan = ((Application) getActivity().getApplication())
				.getUserPlanName();
		if (!strPlan.isEmpty())
			strPlan = strPlan + " Plan";
		planTv.setText(strPlan);
	}

	private void setFocus(EditText et) {
		if (et == null)
			return;
		et.requestFocus();
	}

	@Override
	public void onClick(View v) {

		switch (v.getId()) {
		case R.id.editMyProfileLL:
			Intent editIntent = new Intent(activity, EditProfileActivity.class);
			startActivity(editIntent);
			break;
		case R.id.upgradePlanLL:
			activity.showPlanUpdatePage();
			break;
		case R.id.accountSyncLL:
			Intent moreInfoIntent = new Intent(activity,
					AccountSyncActivity.class);
			startActivity(moreInfoIntent);
			break;
		case R.id.listingSettingsLL:
			Intent listingSettingIntent = new Intent(activity,
					ListingSettingsActivity.class);
			startActivity(listingSettingIntent);
			break;
		case R.id.deleteAccountLL:
			deleteAccountAction();
			break;
		case R.id.logoutLL:
			activity.logOut();
			break;
		}
	}

	// Delete Account Action
	private void deleteAccountAction() {
		AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(
				activity);
		alertDialogBuilder.setTitle("Delete My Account");

		// make custom layout
		final EditText input = new EditText(activity);
		input.setSingleLine(true);
		input.setHint("Enter your password");

		LinearLayout layout = new LinearLayout(activity);
		layout.setOrientation(LinearLayout.VERTICAL);
		LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(
				LinearLayout.LayoutParams.FILL_PARENT,
				LinearLayout.LayoutParams.WRAP_CONTENT);
		params.setMargins(30, 0, 30, 0);
		layout.addView(input, params);

		// add custom layout to dialog
		alertDialogBuilder.setView(layout);
		alertDialogBuilder
				.setMessage(
						"Are you sure that you want to delete your account?")
				.setCancelable(false)
				.setPositiveButton("Delete it Now",
						new DialogInterface.OnClickListener() {
							public void onClick(DialogInterface dialog, int id) {
								dialog.cancel();

								String strPasswd = input.getText().toString();

								// hide keyboard :
								activity.getWindow()
										.setSoftInputMode(
												WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_HIDDEN);

								if (strPasswd.isEmpty()) {
									showToast(
											"Please input your password to delete your account.",
											Toast.LENGTH_LONG);
									return;
								}

								// make parameter
								List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(
										3);
								nameValuePairs.add(new BasicNameValuePair(
										"user_id", String.valueOf(userID)));
								nameValuePairs.add(new BasicNameValuePair(
										"field", "delete"));
								nameValuePairs.add(new BasicNameValuePair(
										"value", strPasswd));
								String paramsString = URLEncodedUtils.format(
										nameValuePairs, "UTF-8");
								String strUrl = GlobalDefine.SiteURLs.setAction
										+ paramsString;

								// start task
								AnyTask deleteAccontTask = new AnyTask(
										activity, deleteAccountTaskCallback,
										"Please wait",
										"Your request is now processing...");
								deleteAccontTask.execute(strUrl);
							}
						})
				.setNegativeButton("Cancel",
						new DialogInterface.OnClickListener() {
							public void onClick(DialogInterface dialog, int id) {
								dialog.cancel();
							}
						});
		AlertDialog alertDialog = alertDialogBuilder.create();
		alertDialog.show();
	}

	AnyTaskCallback deleteAccountTaskCallback = new AnyTaskCallback() {

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
						strResultMsg = "Successfully delete your account.";
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
				activity.logOut();
			}
		}
	};

	private void showToast(String strMsg, int duration) {
		if (strMsg == null || strMsg.isEmpty())
			return;
		Toast.makeText(activity, strMsg, duration).show();
	}
}
