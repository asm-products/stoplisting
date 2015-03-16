package com.listingproduct.stoplisting;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.client.utils.URLEncodedUtils;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.listingproduct.stoplisting.data.StopListingItem;
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
import android.view.View;
import android.view.Window;
import android.view.inputmethod.InputMethodManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.Toast;

public class EditItemActivity extends MyBaseActivity {

	Application mApp;
	Typeface font;
	int userId;
	int itemType;
	int itemPos;
	StopListingItem item = null;

	ImageLoader imgLoader;
	DisplayImageOptions optionsThumb;

	EditText titleEt;
	EditText descriptionEt;
	EditText categoryIdEt;
	EditText priceEt;
	EditText shippingDetailsEt;
	ImageView photoIv;
	ProgressBar progBar;

	String strImagePath;
	String strUiId;
	String strCatId;
	String strTitle;
	String strDetail;
	String strPrice;
	String strShipDetail;
	String strPricingDetail;
	String strUiImage;
	String strUiBarCode;
	String strUiDropBox;
	String strUiStatus;
	String strPath;
	String strClientTime;
	String strModified;
	String strUiDate;

	boolean bUpdated;

	String Tag = "stAboutSearchNotesActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.edit_item_screen);

		mApp = (Application) getApplication();
		font = mApp.getFont();
		userId = mApp.getUserId();
		imgLoader = mApp.getImageLoader();
		// For Image Loader
		optionsThumb = new DisplayImageOptions.Builder()
				.showImageOnLoading(R.drawable.thumb_holder)
				.showImageForEmptyUri(R.drawable.thumb_holder)
				.showImageOnFail(R.drawable.thumb_holder).cacheInMemory(true)
				.cacheOnDisk(true).considerExifParams(true)
				.bitmapConfig(Bitmap.Config.RGB_565).build();

		Intent intent = getIntent();

		itemType = intent.getIntExtra("itemType", -1);
		itemPos = intent.getIntExtra("itemPosition", -1);

		if (itemType == -1 || itemPos == -1) {
			finish();
			return;
		}

		Button backBtn = (Button) findViewById(R.id.backBtn);
		backBtn.setTypeface(font);
		backBtn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				finish();
			}
		});

		Button updateBtn = (Button) findViewById(R.id.updateBtn);
		updateBtn.setTypeface(font);
		updateBtn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				updateItemData();
			}
		});

		titleEt = (EditText) findViewById(R.id.titleEt);
		descriptionEt = (EditText) findViewById(R.id.descriptionEt);
		categoryIdEt = (EditText) findViewById(R.id.CategoryIdEt);
		priceEt = (EditText) findViewById(R.id.priceEt);
		shippingDetailsEt = (EditText) findViewById(R.id.shippingDetailsEt);
		photoIv = (ImageView) findViewById(R.id.itemPhotoIv);
		progBar = (ProgressBar) findViewById(R.id.imgLoadingProg);

		if (itemType == ItemType.ITEM_PUBLISHED) {
			item = mApp.mPublishedItems.get(itemPos);
		} else if (itemType == ItemType.ITEM_UNPUBLISHED) {
			item = mApp.mUnPublishedItems.get(itemPos);
		} else if (itemType == ItemType.ITEM_REJECTED) {
			item = mApp.mRejectedItems.get(itemPos);
		}

		if (item == null) {
			finish();
			return;
		}

		// make parameter
		List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
		nameValuePairs.add(new BasicNameValuePair("user_id", String
				.valueOf(userId)));
		nameValuePairs.add(new BasicNameValuePair("item_id", item.getUiId()));
		String paramsString = URLEncodedUtils.format(nameValuePairs, "UTF-8");

		String strUrl = GlobalDefine.SiteURLs.getItem + paramsString;

		AnyTask getItemInfoTask = new AnyTask(this, getItemInfoTaskCallBack,
				"Please wait", "Getting Item Information...");
		getItemInfoTask.execute(strUrl);
	}

	AnyTaskCallback getItemInfoTaskCallBack = new AnyTaskCallback() {

		@Override
		public void onResult(boolean success, String result) {

			boolean dataReceived = false;
			String strMessage = "";

			if (success) {
				result = result.trim();
				try {
					JSONObject jsonResult = new JSONObject(result);
					String strStatus = jsonResult.getString("status");
					if (strStatus.equals("200")) {
						if (jsonResult.has("image_path"))
							strImagePath = jsonResult.getString("image_path");
						JSONArray itemData = jsonResult
								.getJSONArray("item_data");
						if (itemData.length() > 0) {
							JSONObject data = itemData.getJSONObject(0);

							if (data.has("ui_id"))
								strUiId = data.getString("ui_id");
							if (data.has("category_id"))
								strCatId = data.getString("category_id");
							if (data.has("title"))
								strTitle = data.getString("title");
							if (data.has("detail"))
								strDetail = data.getString("detail");
							if (data.has("price"))
								strPrice = data.getString("price");
							if (data.has("ship_detail"))
								strShipDetail = data.getString("ship_detail");
							if (data.has("pricing_detail"))
								strPricingDetail = data
										.getString("pricing_detail");
							if (data.has("ui_image"))
								strUiImage = data.getString("ui_image");
							if (data.has("ui_barcode"))
								strUiBarCode = data.getString("ui_barcode");
							if (data.has("ui_dropbox"))
								strUiDropBox = data.getString("ui_dropbox");
							if (data.has("ui_status"))
								strUiStatus = data.getString("ui_status");
							if (data.has("path"))
								strPath = data.getString("path");
							if (data.has("client_mtime"))
								strClientTime = data.getString("client_mtime");
							if (data.has("modified"))
								strModified = data.getString("modified");
							if (data.has("ui_date"))
								strUiDate = data.getString("ui_date");

							dataReceived = true;
						} else {
							strMessage = "There is no Item Data.";
						}
					} else {
						strMessage = "Data Retrieving Failure!\nPlease try again.";
					}
				} catch (JSONException e) {
					strMessage = e.getMessage();
					e.printStackTrace();
				}
			} else {
				strMessage = result;
			}

			if (dataReceived) {
				titleEt.setText(strTitle);
				descriptionEt.setText(strDetail);
				categoryIdEt.setText(strCatId);
				priceEt.setText(String.format("%.2f",
						Float.parseFloat(strPrice)));
				shippingDetailsEt.setText(strPricingDetail);

				if (!strImagePath.isEmpty() && !strUiImage.isEmpty()) {
					
					int firstPhotoEndPos = strUiImage.indexOf("-");
					if (firstPhotoEndPos != -1)
						strUiImage = strUiImage.substring(0, firstPhotoEndPos);
					
					String imgUrl = strImagePath + strUiImage;
					imgLoader.displayImage(imgUrl, photoIv, optionsThumb,
							new ImageLoadingListener() {

								@Override
								public void onLoadingStarted(String imageUri,
										View view) {
									progBar.setVisibility(View.VISIBLE);
								}

								@Override
								public void onLoadingFailed(String imageUri,
										View view, FailReason failReason) {
									progBar.setVisibility(View.INVISIBLE);
								}

								@Override
								public void onLoadingComplete(String imageUri,
										View view, Bitmap loadedImage) {
									progBar.setVisibility(View.INVISIBLE);
								}

								@Override
								public void onLoadingCancelled(String imageUri,
										View view) {
									progBar.setVisibility(View.INVISIBLE);
								}
							});
				}

			} else {
				Toast.makeText(EditItemActivity.this, strMessage,
						Toast.LENGTH_LONG).show();
				finish();
			}
		}
	};

	private void removeKeyboard(EditText et) {
		if (et != null) {
			InputMethodManager imm = (InputMethodManager) getSystemService(Context.INPUT_METHOD_SERVICE);
			imm.hideSoftInputFromWindow(et.getWindowToken(), 0);
		}
	}

	private void updateItemData() {

		// hide keyboard
		if (titleEt.isFocused())
			removeKeyboard(titleEt);
		if (descriptionEt.isFocused())
			removeKeyboard(descriptionEt);
		if (categoryIdEt.isFocused())
			removeKeyboard(categoryIdEt);
		if (priceEt.isFocused())
			removeKeyboard(priceEt);
		if (shippingDetailsEt.isFocused())
			removeKeyboard(shippingDetailsEt);

		strTitle = titleEt.getText().toString();
		strDetail = descriptionEt.getText().toString();
		strCatId = categoryIdEt.getText().toString();
		strPrice = priceEt.getText().toString();
		strPricingDetail = shippingDetailsEt.getText().toString();

		// make parameter
		List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(8);
		nameValuePairs.add(new BasicNameValuePair("user_id", String
				.valueOf(userId)));
		nameValuePairs.add(new BasicNameValuePair("item_id", strUiId));
		nameValuePairs.add(new BasicNameValuePair("field", "edit"));
		nameValuePairs.add(new BasicNameValuePair("desc", strDetail));
		nameValuePairs.add(new BasicNameValuePair("title", strTitle));
		nameValuePairs.add(new BasicNameValuePair("cat", strCatId));
		nameValuePairs.add(new BasicNameValuePair("price", strPrice));
		nameValuePairs.add(new BasicNameValuePair("ship", strPricingDetail));
		String paramsString = URLEncodedUtils.format(nameValuePairs, "UTF-8");

		String strUrl = GlobalDefine.SiteURLs.setItem + paramsString;

		AnyTask updateItemInfoTask = new AnyTask(this,
				updateItemInfoTaskCallBack, "Please wait",
				"Updating Item Information...");
		updateItemInfoTask.execute(strUrl);
	}

	AnyTaskCallback updateItemInfoTaskCallBack = new AnyTaskCallback() {

		@Override
		public void onResult(boolean success, String result) {

			String strMessage = "";
			if (success) {
				result = result.trim();
				try {
					JSONObject jsonResult = new JSONObject(result);
					String strStatus = jsonResult.getString("status");
					if (strStatus.equals("200")) {
						strMessage = "Successfully Updated.";
						Intent data = getIntent();
						data.putExtra("itemType", itemType);
						data.putExtra("updated", true);
						setResult(RESULT_OK, data);
						item.setTitle(strTitle);
					} else {
						strMessage = "Data Updating Failure!\nPlease try again.";
					}
				} catch (JSONException e) {
					strMessage = e.getMessage();
					e.printStackTrace();
				}
			} else {
				strMessage = result;
			}

			Toast.makeText(EditItemActivity.this, strMessage, Toast.LENGTH_LONG)
					.show();
		}
	};
}
