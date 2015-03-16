package com.listingproduct.stoplisting;

import java.io.File;
import java.util.ArrayList;

import com.listingproduct.stoplisting.global.GlobalFunc;
import com.listingproduct.stoplisting.utilities.NetStateUtilities;
import com.nostra13.universalimageloader.cache.memory.impl.WeakMemoryCache;
import com.nostra13.universalimageloader.core.DisplayImageOptions;
import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.ImageLoaderConfiguration;
import com.nostra13.universalimageloader.core.assist.ImageScaleType;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Typeface;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.widget.Button;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.ViewSwitcher;

public class ChooseFileActivity extends MyBaseActivity {

	private ArrayList<String> itemPhotoPathList = new ArrayList<String>();

	// Image Count TextView
	private TextView imgCntTv;
	GridView gridGallery;
	GalleryAdapter adapter;
	ImageView imgSinglePick;

	ViewSwitcher viewSwitcher;
	ImageLoader imageLoader;

	public Typeface font;
	String Tag = "stChosoeFileActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.choosefile_screen);

		initImageLoader();

		// Current Item Photo Count TextView
		imgCntTv = (TextView) findViewById(R.id.imgCntTv);

		gridGallery = (GridView) findViewById(R.id.gridGallery);
		gridGallery.setFastScrollEnabled(true);
		adapter = new GalleryAdapter(getApplicationContext(), imageLoader);
		adapter.setMultiplePick(false);
		gridGallery.setAdapter(adapter);

		viewSwitcher = (ViewSwitcher) findViewById(R.id.viewSwitcher);
		viewSwitcher.setDisplayedChild(1);

		imgSinglePick = (ImageView) findViewById(R.id.imgSinglePick);

		// load Font
		font = ((Application) getApplication()).getFont();

		// Close button
		Button closeBtn = (Button) findViewById(R.id.closeBtn);
		closeBtn.setTypeface(font);
		closeBtn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				finish();
			}
		});

		// Camera button
		Button galleryBtn = (Button) findViewById(R.id.galleryBtn);
		galleryBtn.setTypeface(font);
		galleryBtn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				// get an image from the gallery
				Intent i = new Intent(Action.ACTION_MULTIPLE_PICK);
				startActivityForResult(i, 200);
			}
		});

		// Upload button
		Button uploadBtn = (Button) findViewById(R.id.uploadBtn);
		uploadBtn.setTypeface(font);
		uploadBtn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				// upload Current Item Photos
				uploadCurItemPhotos();
			}
		});

	}

	private void initImageLoader() {
		DisplayImageOptions defaultOptions = new DisplayImageOptions.Builder()
				.cacheOnDisc().imageScaleType(ImageScaleType.EXACTLY_STRETCHED)
				.bitmapConfig(Bitmap.Config.RGB_565).build();
		ImageLoaderConfiguration.Builder builder = new ImageLoaderConfiguration.Builder(
				this).defaultDisplayImageOptions(defaultOptions).memoryCache(
				new WeakMemoryCache());

		ImageLoaderConfiguration config = builder.build();
		imageLoader = ImageLoader.getInstance();
		imageLoader.init(config);
	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		super.onActivityResult(requestCode, resultCode, data);

		if (requestCode == 200 && resultCode == Activity.RESULT_OK) {
			ArrayList<String> all_path = (ArrayList<String>) data
					.getSerializableExtra("all_path");

			// There is no photo to list.
			if (all_path == null || all_path.size() == 0)
				return;

			ArrayList<CustomGallery> dataT = new ArrayList<CustomGallery>();

			itemPhotoPathList.clear();
			itemPhotoPathList = null;
			itemPhotoPathList = all_path;
			for (String string : all_path) {
				CustomGallery item = new CustomGallery();
				item.sdcardPath = string;
				dataT.add(item);
			}

			viewSwitcher.setDisplayedChild(0);
			adapter.addAll(dataT);

			String strTopTitle = String.format("%s", itemPhotoPathList.size());
			imgCntTv.setText(strTopTitle);

		}
	}

	private void uploadCurItemPhotos() {
		if (itemPhotoPathList == null || itemPhotoPathList.size() == 0) {
			showToast("There is no Photos for uploading new item.");
			return;
		}

		int nFiles = itemPhotoPathList.size();
		for (int i = 0; i < nFiles; i++) {
			String curPath = itemPhotoPathList.get(i);
			if (curPath.indexOf("-") != -1) {
				File file = new File(curPath);
				if (file.exists()) {
					String newPath = curPath.replaceAll("-", "_");
					File newFile = new File(newPath);
					if (file.renameTo(newFile)) {
						itemPhotoPathList.set(i, newPath);
					}
				}
			}
		}

		// Network state check
		if (!NetStateUtilities.hasConnection(this)) {

			AlertDialog.Builder builder = new AlertDialog.Builder(this);

			// set property
			builder.setTitle("Notice")
					.setMessage(
							"Cannot list current item. No network connection.")
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

		((Application) getApplication()).listNewitem(itemPhotoPathList);
		resetItem();

		/*
		 * DropboxAPI<AndroidAuthSession> mApi; mApi = ((Application)
		 * getApplication()).getDropboxApi();
		 * 
		 * UploadPicture upload = new UploadPicture(this, mApi, PHOTO_DIR,
		 * itemPhotoPathList, null); upload.execute();
		 */
	}

	private void showToast(String msg) {
		Toast error = Toast.makeText(this, msg, Toast.LENGTH_LONG);
		error.show();
	}

	private void resetItem() {
		itemPhotoPathList = new ArrayList<String>();
		// Update Top title
		String strTopTitle = String.format("%s", itemPhotoPathList.size());
		imgCntTv.setText(strTopTitle);
		viewSwitcher.setDisplayedChild(1);
	}
}
