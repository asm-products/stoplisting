package com.listingproduct.stoplisting;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.client.utils.URLEncodedUtils;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.listingproduct.stoplisting.data.TemplateItem;
import com.listingproduct.stoplisting.global.GlobalDefine;
import com.listingproduct.stoplisting.task.AnyTask;
import com.listingproduct.stoplisting.task.AnyTaskCallback;
import com.nostra13.universalimageloader.core.DisplayImageOptions;
import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.assist.FailReason;
import com.nostra13.universalimageloader.core.listener.ImageLoadingListener;

import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Typeface;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.view.Window;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

public class ListingSettingsActivity extends MyBaseActivity implements
		OnClickListener {

	Application application;
	int userID;
	ImageLoader imgLoader;
	DisplayImageOptions optionsThumb;

	View actionPanel;
	Spinner spinner;

	ArrayList<TemplateItem> templateList = new ArrayList<TemplateItem>();

	String Tag = "stListingSettingsActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.listing_settings_screen);

		application = (Application) getApplication();
		userID = application.getUserId();
		imgLoader = application.getImageLoader();
		// For Image Loader
		optionsThumb = new DisplayImageOptions.Builder()
				.showImageOnLoading(R.drawable.thumb_holder)
				.showImageForEmptyUri(R.drawable.thumb_holder)
				.showImageOnFail(R.drawable.thumb_holder).cacheInMemory(true)
				.cacheOnDisk(true).considerExifParams(true)
				.bitmapConfig(Bitmap.Config.RGB_565).build();

		Typeface font = application.getFont();

		TextView iconDefaultTemplate = (TextView) findViewById(R.id.iconDefaultTemplate);
		TextView iconGeneralCond = (TextView) findViewById(R.id.iconGeneralCond);
		TextView iconPricePref = (TextView) findViewById(R.id.iconPricePref);
		TextView iconListingPref = (TextView) findViewById(R.id.iconListingPref);

		iconDefaultTemplate.setTypeface(font);
		iconGeneralCond.setTypeface(font);
		iconPricePref.setTypeface(font);
		iconListingPref.setTypeface(font);

		View defaultTemplateLL = findViewById(R.id.defaultTemplateLL);
		defaultTemplateLL.setOnClickListener(this);
		actionPanel = findViewById(R.id.templateActionBar);
		spinner = (Spinner) findViewById(R.id.spinner);

		View generalCondLL = findViewById(R.id.generalCondLL);
		generalCondLL.setOnClickListener(this);
		View pricePrefLL = findViewById(R.id.pricePrefLL);
		pricePrefLL.setOnClickListener(this);
		View listingPrefLL = findViewById(R.id.listingPrefLL);
		listingPrefLL.setOnClickListener(this);

		Button backBtn = (Button) findViewById(R.id.backBtn);
		backBtn.setTypeface(font);
		backBtn.setOnClickListener(this);

		// Start a Task for retrieving the template information
		List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
		nameValuePairs.add(new BasicNameValuePair("user_id", String
				.valueOf(userID)));
		nameValuePairs.add(new BasicNameValuePair("field", "all_templates"));
		String paramsString = URLEncodedUtils.format(nameValuePairs, "UTF-8");
		String strUrl = GlobalDefine.SiteURLs.getAction + paramsString;

		AnyTask retriveTemplateInfoTask = new AnyTask(this,
				retrieveTemplateInfoTaskCallBack, "Please wait",
				"Retrieving all template information...");

		retriveTemplateInfoTask.execute(strUrl);
		/*
		 * retriveTemplateInfoTask .execute(
		 * "http://agapeworks.x10.mx/stoplisting.com/api/?getact&user_id=1&field=all_templates"
		 * );
		 */
	}

	AnyTaskCallback retrieveTemplateInfoTaskCallBack = new AnyTaskCallback() {

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
						int nTemplates = 0;
						if (userDataJsons != null)
							nTemplates = userDataJsons.length();

						templateList.clear();
						for (int i = 0; i < nTemplates; i++) {
							JSONObject templateData = userDataJsons
									.getJSONObject(i);
							if (templateData == null)
								continue;
							TemplateItem newItem = new TemplateItem();
							newItem.setTempId(templateData.getInt("temp_id"));
							newItem.setUserId(templateData.getInt("user_id"));
							newItem.setTitle(templateData
									.getString("temp_title"));
							newItem.setImageUrl(templateData
									.getString("temp_image"));
							templateList.add(newItem);
						}

						if (templateList.size() > 0) {
							TemplateItemAdapter templateAdapter = new TemplateItemAdapter(
									ListingSettingsActivity.this, templateList);
							spinner.setAdapter(templateAdapter);
							
							// start Task for getting MyTemplate Info
							startMyTemplateGettingTask();
						}
					}
				} catch (JSONException e) {
					e.printStackTrace();
				}
			}
		}
	};

	private void startMyTemplateGettingTask() {
		// Start a Task for retrieving the template information
		List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
		nameValuePairs.add(new BasicNameValuePair("user_id", String
				.valueOf(userID)));
		nameValuePairs.add(new BasicNameValuePair("field", "default_template"));
		String paramsString = URLEncodedUtils.format(nameValuePairs, "UTF-8");
		String strUrl = GlobalDefine.SiteURLs.getAction + paramsString;

		AnyTask gettingMyTemplateTask = new AnyTask(this,
				gettingMyTemplateTaskCallBack, "Please wait",
				"Retrieving default template information...");

		gettingMyTemplateTask.execute(strUrl);
	}

	AnyTaskCallback gettingMyTemplateTaskCallBack = new AnyTaskCallback() {

		@Override
		public void onResult(boolean success, String result) {

			if (success) {
				result = result.trim();
				try {
					JSONObject jsonResult = new JSONObject(result);
					String strStatus = jsonResult.getString("status");
					if (strStatus.equals("200")) {
						if (jsonResult.has("user_data")) {
							JSONArray userDataJsons = jsonResult
									.getJSONArray("user_data");
							if (userDataJsons.length() > 0) {
								JSONObject userData = userDataJsons.getJSONObject(0);
								// Parse userData
							}
						}

					}
				} catch (JSONException e) {
					e.printStackTrace();
				}
			}
		}
	};

	public class TemplateItemAdapter extends BaseAdapter {

		Context mContext;
		ArrayList<TemplateItem> itemLists;

		public TemplateItemAdapter(Context ctx, ArrayList<TemplateItem> objects) {
			mContext = ctx;
			itemLists = objects;
		}

		@Override
		public int getCount() {
			return itemLists.size();
		}

		@Override
		public TemplateItem getItem(int position) {
			// TODO Auto-generated method stub
			return itemLists.get(position);
		}

		@Override
		public long getItemId(int position) {
			return position;
		}

		@Override
		public View getView(int pos, View convertView, ViewGroup parent) {
			View view = convertView;
			if (convertView == null) {
				view = LayoutInflater.from(mContext).inflate(
						R.layout.custom_spinner, null);
			}

			// set data
			TemplateItem curItem = getItem(pos);
			String templateTitle = curItem.getTitle();
			String templateUrl = curItem.getImageUrl();

			final ProgressBar imgLoadingProg = (ProgressBar) view
					.findViewById(R.id.loadingProgBar);
			ImageView thumbIv = (ImageView) view.findViewById(R.id.thumbIv);
			TextView titleTv = (TextView) view.findViewById(R.id.titleTv);
			titleTv.setText(templateTitle);

			imgLoader.displayImage(templateUrl, thumbIv, optionsThumb,
					new ImageLoadingListener() {

						@Override
						public void onLoadingStarted(String imageUri, View view) {
							imgLoadingProg.setVisibility(View.VISIBLE);
						}

						@Override
						public void onLoadingFailed(String imageUri, View view,
								FailReason failReason) {
							imgLoadingProg.setVisibility(View.INVISIBLE);
						}

						@Override
						public void onLoadingComplete(String imageUri,
								View view, Bitmap loadedImage) {
							imgLoadingProg.setVisibility(View.INVISIBLE);
						}

						@Override
						public void onLoadingCancelled(String imageUri,
								View view) {
							imgLoadingProg.setVisibility(View.INVISIBLE);
						}
					});
			return view;
		}

	}

	@Override
	public void onClick(View v) {
		switch (v.getId()) {
		case R.id.backBtn:
			finish();
			break;
		case R.id.defaultTemplateLL:

			if (actionPanel.getVisibility() == View.VISIBLE) {
				actionPanel.setVisibility(View.GONE);
			} else {
				actionPanel.setVisibility(View.VISIBLE);
			}
			break;
		case R.id.generalCondLL:
			startActivity(new Intent(ListingSettingsActivity.this,
					WGeneralCondition1Activity.class));
			break;
		case R.id.pricePrefLL:
			startActivity(new Intent(ListingSettingsActivity.this,
					WPricePreference1Activity.class));
			break;
		case R.id.listingPrefLL:
			startActivity(new Intent(ListingSettingsActivity.this,
					WListingPreference1Activity.class));
			break;
		case R.id.saveBtn:
			showToast("Please add real action here.", Toast.LENGTH_LONG);
			// setTemplate();
			break;
		}
	}

	private void setTemplate() {
		// spinner.getSelectedItem();
		// Start a Task for retrieving the template information
		List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
		nameValuePairs.add(new BasicNameValuePair("user_id", String
				.valueOf(userID)));
		nameValuePairs.add(new BasicNameValuePair("field", "default_template"));
		//nameValuePairs.add(new BasicNameValuePair("value", ));
		
		String paramsString = URLEncodedUtils.format(nameValuePairs, "UTF-8");
		String strUrl = GlobalDefine.SiteURLs.getAction + paramsString;
		AnyTask saveTemplateInfoTask = new AnyTask(this,
				setTemplateInfoTaskCallBack, "Please wait",
				"Retrieving the template information...");
		saveTemplateInfoTask.execute(strUrl);
	}

	AnyTaskCallback setTemplateInfoTaskCallBack = new AnyTaskCallback() {

		@Override
		public void onResult(boolean success, String result) {

			if (success) {
				result = result.trim();
				try {
					JSONObject jsonResult = new JSONObject(result);
					String strStatus = jsonResult.getString("status");
					if (strStatus.equals("200")) {
						// TO DO here
						/*JSONArray userDataJsons = jsonResult
								.getJSONArray("user_data");
						JSONObject userData = userDataJsons.getJSONObject(0);*/						
					}
				} catch (JSONException e) {
					e.printStackTrace();
				}
			}
		}
	};

	private void showToast(String strMsg, int duration) {
		if (strMsg == null || strMsg.isEmpty())
			return;
		Toast.makeText(this, strMsg, duration).show();
	}
}
