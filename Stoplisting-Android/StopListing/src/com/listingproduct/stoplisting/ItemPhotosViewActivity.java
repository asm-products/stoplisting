package com.listingproduct.stoplisting;

import java.io.File;
import java.util.ArrayList;

import com.listingproduct.stoplisting.global.GlobalFunc;
import com.listingproduct.stoplisting.utilities.ImageUtils;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.support.v4.view.PagerAdapter;
import android.support.v4.view.ViewPager;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.widget.ImageView;
import android.widget.LinearLayout;

public class ItemPhotosViewActivity extends MyBaseActivity {

	ArrayList<String> itemFileList;
	int mViewWidth;
	int mViewHeight;

	String Tag = "stItemPhotoViewActivity";
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);

		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.item_photo_slide_screen);
		
		// Get Data
		Intent intent = getIntent();
		itemFileList = (ArrayList<String>) intent
				.getSerializableExtra("filelist");
		checkFilesExist();

		ViewPager viewPager = (ViewPager) findViewById(R.id.view_pager);
		mViewWidth = viewPager.getWidth();
		mViewHeight = viewPager.getHeight();

		ImagePagerAdapter adapter = new ImagePagerAdapter();
		adapter.setDataList(itemFileList);
		viewPager.setAdapter(adapter);

		final GalleryNavigator navi = (GalleryNavigator) findViewById(R.id.navi);
		navi.setSize(itemFileList.size());
		
		viewPager.setOnPageChangeListener(new ViewPager.OnPageChangeListener() {
		    public void onPageScrollStateChanged(int state) {}
		    public void onPageScrolled(int position, float positionOffset, int positionOffsetPixels) {}
		    public void onPageSelected(int position) {
		    	GlobalFunc.viewLog(Tag, "Page Changed"  + position, true);
		    	navi.setPosition(position);
		    }
		});
	}

	// check File exist
	private void checkFilesExist() {
		int nFiles = itemFileList.size();
		if (nFiles == 0)
			return;

		for (int i = nFiles - 1; i >= 0; i--) {
			File file = new File(itemFileList.get(i));
			if (!file.exists()) {
				itemFileList.remove(i);
			}
		}
	}

	private class ImagePagerAdapter extends PagerAdapter {

		ArrayList<String> imgPathList;

		public void setDataList(ArrayList<String> list) {
			imgPathList = list;
		}

		@Override
		public int getCount() {
			if (imgPathList == null)
				return 0;
			return imgPathList.size();
		}

		@Override
		public boolean isViewFromObject(View view, Object object) {
			return view == ((ImageView) object);
		}

		@Override
		public Object instantiateItem(ViewGroup container, int position) {
			Context context = ItemPhotosViewActivity.this;
			TouchImageView imageView = new TouchImageView(
					container.getContext());
			/*
			 * int padding = context.getResources().getDimensionPixelSize(
			 * R.dimen.padding_small); imageView.setPadding(padding, padding,
			 * padding, padding);
			 */
			// imageView.setScaleType(ImageView.ScaleType.FIT_XY);
			imageView.setScaleType(ImageView.ScaleType.FIT_CENTER);

			File imgFile = new File(imgPathList.get(position));
			if (imgFile.exists()) {
				Bitmap myBitmap = BitmapFactory.decodeFile(imgFile
						.getAbsolutePath());
				if (myBitmap.getWidth() > myBitmap.getHeight()) {
					// width > height => rotate 90 degrees
					Bitmap rotateBmp = ImageUtils.rotateBitmap(myBitmap, 90);
					imageView.setImageBitmap(rotateBmp);
				} else {
					// height > width
					imageView.setImageBitmap(myBitmap);
				}

				((ViewPager) container).addView(imageView,
						LinearLayout.LayoutParams.MATCH_PARENT,
						LinearLayout.LayoutParams.MATCH_PARENT);
			}

			return imageView;
		}

		@Override
		public void destroyItem(ViewGroup container, int position, Object object) {
			((ViewPager) container).removeView((ImageView) object);
		}
	}
}