package com.listingproduct.stoplisting;

import com.listingproduct.stoplisting.task.LiveFinderItemListingTask;
import com.listingproduct.stoplisting.task.LiveFinderItemRejectingTask;
import com.nostra13.universalimageloader.core.DisplayImageOptions;
import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.assist.FailReason;
import com.nostra13.universalimageloader.core.listener.ImageLoadingListener;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.Window;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

public class LiveFinderResultActivity extends MyBaseActivity implements
		OnClickListener {

	int mLiveFinderId;
	String mRefUrl;
	String Tag = "stLiveFinderResultActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.livefinder_result_screen);

		// Read Item data
		Intent data = getIntent();
		mLiveFinderId = data.getIntExtra("liveFinderID", -1);
		if (mLiveFinderId == -1) {
			Toast.makeText(this, "Cannot find LiveFinder ID.",
					Toast.LENGTH_LONG).show();
			finish();
			return;
		}

		String strSwankRank = data.getStringExtra("swankRank");
		String strAvgPrice = data.getStringExtra("avgPrice");
		String strItemTitle = data.getStringExtra("itemTitle");
		String strPhotoUrl = data.getStringExtra("photoUrl");
		mRefUrl = data.getStringExtra("refUrl");
		// About "Back" and "TakeAnotherPhoto" button,
		// for further extended function, use different return value
		Button backBtn = (Button) findViewById(R.id.backBtn);
		backBtn.setTypeface(((Application) getApplication()).getFont());
		backBtn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				Intent data = getIntent();
				data.putExtra("returnState", false);
				setResult(RESULT_OK, data);
				finish();
			}
		});

		Button takeAnotherPhotoBtn = (Button) findViewById(R.id.takeAnotherPhotoBtn);
		takeAnotherPhotoBtn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				Intent data = getIntent();
				data.putExtra("returnState", true);
				setResult(RESULT_OK, data);
				finish();
			}
		});

		// Item Swank Rank
		TextView swankRankTv = (TextView) findViewById(R.id.swankRankTv);
		swankRankTv.setText(strSwankRank);

		// Item Price
		TextView priceTv = (TextView) findViewById(R.id.priceTv);
		swankRankTv.setText("$" + strAvgPrice);

		// Item Photo
		ImageView itemPhotoIv = (ImageView) findViewById(R.id.itemPhotoIv);
		final ProgressBar imgLoadingProg = (ProgressBar) findViewById(R.id.imgLoadingProg);
		ImageLoader imgLoader = ((Application) getApplication())
				.getImageLoader();
		DisplayImageOptions options;
		// For Image Loader
		options = new DisplayImageOptions.Builder()
				.showImageOnLoading(R.drawable.placeholder)
				.showImageForEmptyUri(R.drawable.placeholder)
				.showImageOnFail(R.drawable.placeholder).cacheInMemory(true)
				.cacheOnDisk(true).considerExifParams(true)
				.bitmapConfig(Bitmap.Config.RGB_565).build();

		// imgLoader.displayImage(photoUrl, PhotoIv, options);
		imgLoader.displayImage(strPhotoUrl, itemPhotoIv, options,
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
					public void onLoadingComplete(String imageUri, View view,
							Bitmap loadedImage) {
						imgLoadingProg.setVisibility(View.INVISIBLE);
					}

					@Override
					public void onLoadingCancelled(String imageUri, View view) {
						imgLoadingProg.setVisibility(View.INVISIBLE);
					}

				});

		// Item Title
		TextView itemTitleTv = (TextView) findViewById(R.id.itemTitleTv);
		itemTitleTv.setText(strItemTitle);

		// Action Buttons
		Button listItemBtn = (Button) findViewById(R.id.listItemBtn);
		Button wrongItemBtn = (Button) findViewById(R.id.wrongItemBtn);
		listItemBtn.setOnClickListener(this);
		wrongItemBtn.setOnClickListener(this);
	}

	@Override
	public void onClick(View v) {
		int viewID = v.getId();
		switch (viewID) {
		case R.id.listItemBtn:
			LiveFinderItemListingTask itemListingTask = new LiveFinderItemListingTask(this, mLiveFinderId);
			itemListingTask.start();
			break;

		case R.id.wrongItemBtn:
			LiveFinderItemRejectingTask itemRejectingTask = new LiveFinderItemRejectingTask(this, mLiveFinderId);
			itemRejectingTask.start();
			break;
		}
	}

}
