package com.listingproduct.stoplisting.fragment;

import java.util.ArrayList;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.CompoundButton.OnCheckedChangeListener;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.listingproduct.stoplisting.AccountSyncActivity;
import com.listingproduct.stoplisting.Application;
import com.listingproduct.stoplisting.EditItemActivity;
import com.listingproduct.stoplisting.ItemType;
import com.listingproduct.stoplisting.MainActivity;
import com.listingproduct.stoplisting.R;
import com.listingproduct.stoplisting.data.StopListingItem;
import com.listingproduct.stoplisting.task.AnyTaskCallback;
import com.listingproduct.stoplisting.task.ListItemTask;
import com.listingproduct.stoplisting.task.RemoveItemTask;
import com.nostra13.universalimageloader.core.DisplayImageOptions;
import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.assist.FailReason;
import com.nostra13.universalimageloader.core.listener.ImageLoadingListener;

public class ManageFragment extends Fragment implements OnClickListener {

	TextView leftTabTv;
	TextView rightTabTv;

	Application mApp;
	int userId;
	MainActivity mActivity;

	ImageLoader imgLoader;
	DisplayImageOptions optionsPhoto;

	View page1;
	View page2;
	View page3;
	ListView unPubedItemLV;
	ListView rejectedItemLV;
	UnPublishItemsAdapter mAdapter1;
	RejectedItemsAdapter mAdapter2;

	private static final int REQ_CODE_UNPUBED = 200;
	private static final int REQ_CODE_REJECTED = 300;

	TextView noUnPubedItemTv;
	TextView noRejectedItemTv;

	ArrayList<StopListingItem> unPubedItems;
	ArrayList<StopListingItem> rejectedItems;

	int mDesiredPage = 0;

	String Tag = "stManageFragment";

	public ManageFragment(int page) {
		mDesiredPage = page;
	}

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {

		mActivity = (MainActivity) getActivity();
		mApp = (Application) mActivity.getApplication();
		userId = mApp.getUserId();

		// For Image Loader
		imgLoader = mApp.getImageLoader();
		optionsPhoto = new DisplayImageOptions.Builder()
				.showImageOnLoading(R.drawable.white_back)
				.showImageForEmptyUri(R.drawable.no_media)
				.showImageOnFail(R.drawable.no_media).cacheInMemory(true)
				.cacheOnDisk(true).considerExifParams(true)
				.bitmapConfig(Bitmap.Config.RGB_565).build();

		View view = inflater.inflate(R.layout.manage_screen, container, false);

		leftTabTv = (TextView) view.findViewById(R.id.leftTabText);
		rightTabTv = (TextView) view.findViewById(R.id.rightTabText);

		// get tabs and set actions
		View leftTab = view.findViewById(R.id.leftTab);
		View rightTab = view.findViewById(R.id.rightTab);
		leftTab.setOnClickListener(this);
		rightTab.setOnClickListener(this);

		page1 = view.findViewById(R.id.page1);
		page2 = view.findViewById(R.id.page2);
		page3 = view.findViewById(R.id.page3);

		unPubedItemLV = (ListView) view.findViewById(R.id.itemUnPublishedList);
		rejectedItemLV = (ListView) view.findViewById(R.id.itemRejectedList);

		noUnPubedItemTv = (TextView) view.findViewById(R.id.noUnpubedItemTv);
		noRejectedItemTv = (TextView) view.findViewById(R.id.noRejectedItemTv);

		CheckBox showAgainCheck = (CheckBox) view
				.findViewById(R.id.showAgainCheck);
		showAgainCheck
				.setOnCheckedChangeListener(new OnCheckedChangeListener() {

					@Override
					public void onCheckedChanged(CompoundButton buttonView,
							boolean isChecked) {
						mActivity.hideEbayAuthDlgOnManagedItempage = isChecked;
					}
				});

		Button noThanksBtn = (Button) view.findViewById(R.id.noThanksBtn);
		noThanksBtn.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				page3.setVisibility(View.GONE);
			}
		});

		Button setNowBtn = (Button) view.findViewById(R.id.setNowBtn);
		setNowBtn.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				Intent intent = new Intent(mActivity, AccountSyncActivity.class);
				startActivity(intent);
				page3.setVisibility(View.GONE);
			}
		});

		if (!mApp.getTokenStatus()
				&& !mActivity.hideEbayAuthDlgOnManagedItempage) {
			page3.setVisibility(View.VISIBLE);
		} else {
			page3.setVisibility(View.GONE);
		}

		selectTab(mDesiredPage);
		return view;
	}

	@Override
	public void onViewCreated(View view, Bundle savedInstanceState) {
		super.onViewCreated(view, savedInstanceState);
		getUnPublishItems();
		getRejectedItems();

		mAdapter1 = new UnPublishItemsAdapter(getActivity(), unPubedItems);
		unPubedItemLV.setAdapter(mAdapter1);
		if (unPubedItems.size() == 0)
			noUnPubedItemTv.setVisibility(View.VISIBLE);
		else
			noUnPubedItemTv.setVisibility(View.GONE);

		mAdapter2 = new RejectedItemsAdapter(getActivity(), rejectedItems);
		rejectedItemLV.setAdapter(mAdapter2);
		if (rejectedItems.size() == 0)
			noRejectedItemTv.setVisibility(View.VISIBLE);
		else
			noRejectedItemTv.setVisibility(View.GONE);
	}

	private void getUnPublishItems() {
		/*
		 * unPublishedItems.clear();
		 * 
		 * unPublishedItems .add(new StopListingItem(
		 * "BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa", 200,
		 * "07/05/15", "New", "Fixed Price", 7, 0x7, "Minimal Template #1"));
		 * unPublishedItems .add(new StopListingItem(
		 * "BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa", 150,
		 * "06/05/15", "New", "Fixed Price", 3, 0x1, "Minimal Template #2"));
		 * unPublishedItems .add(new StopListingItem(
		 * "BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa", 250,
		 * "05/05/15", "New", "Fixed Price", 1, 0x3, "Minimal Template #3"));
		 * unPublishedItems .add(new StopListingItem(
		 * "BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa", 300,
		 * "04/05/15", "New", "Fixed Price", 7, 0x2, "Minimal Template #4"));
		 */
		unPubedItems = mApp.mUnPublishedItems;

	}

	private void getRejectedItems() {
		/*
		 * rejectedItems.clear();
		 * 
		 * rejectedItems .add(new StopListingItem(
		 * "BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa", 200,
		 * "07/05/15", "New", "Fixed Price", 7, 0x7, "Minimal Template #1"));
		 * rejectedItems .add(new StopListingItem(
		 * "BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa", 150,
		 * "06/05/15", "New", "Fixed Price", 3, 0x1, "Minimal Template #2"));
		 * rejectedItems .add(new StopListingItem(
		 * "BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa", 250,
		 * "05/05/15", "New", "Fixed Price", 1, 0x3, "Minimal Template #3"));
		 * rejectedItems .add(new StopListingItem(
		 * "BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa", 300,
		 * "04/05/15", "New", "Fixed Price", 7, 0x2, "Minimal Template #4"));
		 */
		rejectedItems = mApp.mRejectedItems;

	}

	@Override
	public void onClick(View v) {

		switch (v.getId()) {
		case R.id.leftTab:
			selectTab(0);
			break;
		case R.id.rightTab:
			selectTab(1);
			break;
		}
	}

	private void selectTab(int nIndex) {
		TextView actTabTv, inActTabTv;
		View actPage, inActPage;

		if (nIndex == 0) {
			actTabTv = leftTabTv;
			inActTabTv = rightTabTv;
			actPage = page1;
			inActPage = page2;
		} else {
			actTabTv = rightTabTv;
			inActTabTv = leftTabTv;
			actPage = page2;
			inActPage = page1;
		}

		// act and inact TextView
		actTabTv.setBackgroundColor(getResources().getColor(
				R.color.tab_sel_bg_color));
		actTabTv.setTextColor(getResources().getColor(
				R.color.tab_sel_text_color));
		inActTabTv.setBackgroundColor(getResources().getColor(
				R.color.tab_bg_color));
		inActTabTv
				.setTextColor(getResources().getColor(R.color.tab_text_color));

		// act and inactive page
		actPage.setVisibility(View.VISIBLE);
		inActPage.setVisibility(View.INVISIBLE);

		boolean bTokenSet = mApp.getTokenStatus();
	}

	public class UnPublishItemsAdapter extends BaseAdapter implements
			OnClickListener {

		Context mContext;
		ArrayList<StopListingItem> itemLists;

		public UnPublishItemsAdapter(Context context,
				ArrayList<StopListingItem> list) {

			mContext = context;
			itemLists = list;
		}

		@Override
		public int getCount() {

			return itemLists.size();
		}

		@Override
		public long getItemId(int position) {
			return position;
		}

		@Override
		public StopListingItem getItem(int position) {
			return itemLists.get(position);
		}

		@Override
		public View getView(int position, View convertView, ViewGroup parent) {

			View view = convertView;
			if (convertView == null) {
				view = LayoutInflater.from(mContext).inflate(
						R.layout.unpublished_listitem, null);
			}

			// set data
			StopListingItem curItem = getItem(position);

			ImageView itemPhotoIv = (ImageView) view
					.findViewById(R.id.itemPhotoIv);
			final ProgressBar progBar = (ProgressBar) view
					.findViewById(R.id.progBar);

			TextView itemTitleTv = (TextView) view
					.findViewById(R.id.itemTitleTv);
			TextView itemPriceTv = (TextView) view
					.findViewById(R.id.itemPriceTv);
			TextView itemEditBtn = (TextView) view
					.findViewById(R.id.itemEditBtn);
			TextView itemRemoveBtn = (TextView) view
					.findViewById(R.id.itemRemoveBtn);
			TextView itemListBtn = (TextView) view
					.findViewById(R.id.itemListBtn);

			String strPrice = String.format("%.02f", curItem.getPrice());
			itemPriceTv.setText("$" + strPrice);

			String strTitle = curItem.getTitle().trim();
			if (!strTitle.isEmpty()) {
				itemTitleTv.setText(strTitle);
			} else {
				itemTitleTv.setText("There is no item title to show.");
			}

			String strPhotoFile = curItem.getPhotoUrl();
			if (!strPhotoFile.isEmpty()) {
				int firstPhotoEndPos = strPhotoFile.indexOf("-");
				if (firstPhotoEndPos != -1)
					strPhotoFile = strPhotoFile.substring(0, firstPhotoEndPos);
				
				String strPhotoUrl = mApp.mImagePath + strPhotoFile;
				imgLoader.displayImage(strPhotoUrl, itemPhotoIv, optionsPhoto,
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

			// set Tag for event processing and onclickListener
			itemEditBtn.setTag(position);
			itemRemoveBtn.setTag(position);
			itemListBtn.setTag(position);

			itemEditBtn.setOnClickListener(this);
			itemRemoveBtn.setOnClickListener(this);
			itemListBtn.setOnClickListener(this);

			return view;
		}

		@Override
		public void onClick(View v) {
			String strTag = v.getTag().toString();
			int viewID = v.getId();
			int viewIndex = Integer.parseInt(strTag);

			switch (viewID) {
			case R.id.itemEditBtn:
				Intent intent = new Intent(mContext, EditItemActivity.class);
				intent.putExtra("itemType", ItemType.ITEM_UNPUBLISHED);
				intent.putExtra("itemPosition", viewIndex);
				startActivityForResult(intent, REQ_CODE_UNPUBED);
				break;
			case R.id.itemRemoveBtn:
				removeUnPubedItem(viewIndex);
				break;
			case R.id.itemListBtn:
				listUnPubedItem(viewIndex);
				break;
			}
		}

		private void removeUnPubedItem(int pos) {

			RemoveItemTask removeItemTask = new RemoveItemTask(mContext,
					setItemTaskCallBack, userId, unPubedItems.get(pos)
							.getUiId());
			removeItemTask.execute("");
		}

		private void listUnPubedItem(int pos) {
			// Start a Task for retrieving the template information
			ListItemTask listItemTask = new ListItemTask(mContext,
					setItemTaskCallBack, userId, unPubedItems.get(pos)
							.getUiId());
			listItemTask.execute("");
		}
	}

	public class RejectedItemsAdapter extends BaseAdapter implements
			OnClickListener {

		Context mContext;
		ArrayList<StopListingItem> itemLists;

		public RejectedItemsAdapter(Context context,
				ArrayList<StopListingItem> list) {

			mContext = context;
			itemLists = list;
		}

		@Override
		public int getCount() {

			return itemLists.size();
		}

		@Override
		public long getItemId(int position) {
			return position;
		}

		@Override
		public StopListingItem getItem(int position) {
			return itemLists.get(position);
		}

		@Override
		public View getView(int position, View convertView, ViewGroup parent) {

			View view = convertView;
			if (convertView == null) {
				view = LayoutInflater.from(mContext).inflate(
						R.layout.rejected_listitem, null);
			}

			// set data
			StopListingItem curItem = getItem(position);

			ImageView itemPhotoIv = (ImageView) view
					.findViewById(R.id.itemPhotoIv);
			final ProgressBar progBar = (ProgressBar) view
					.findViewById(R.id.progBar);

			TextView itemTitleTv = (TextView) view
					.findViewById(R.id.itemTitleTv);
			TextView itemPriceTv = (TextView) view
					.findViewById(R.id.itemPriceTv);
			TextView itemEditBtn = (TextView) view
					.findViewById(R.id.itemEditBtn);
			TextView itemRemoveBtn = (TextView) view
					.findViewById(R.id.itemRemoveBtn);
			TextView itemReListBtn = (TextView) view
					.findViewById(R.id.itemReListBtn);

			String strPrice = String.format("%.02f", curItem.getPrice());
			itemPriceTv.setText("$" + strPrice);

			String strTitle = curItem.getTitle().trim();
			if (!strTitle.isEmpty()) {
				itemTitleTv.setText(strTitle);
			} else {
				itemTitleTv.setText("There is no item title to show.");
			}

			String strPhotoFile = curItem.getPhotoUrl();
			if (!strPhotoFile.isEmpty()) {
				int firstPhotoEndPos = strPhotoFile.indexOf("-");
				if (firstPhotoEndPos != -1)
					strPhotoFile = strPhotoFile.substring(0, firstPhotoEndPos);
				 
				String strPhotoUrl = mApp.mImagePath + strPhotoFile;
				imgLoader.displayImage(strPhotoUrl, itemPhotoIv, optionsPhoto,
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

			// set Tag for event processing and onclickListener
			itemEditBtn.setTag(position);
			itemRemoveBtn.setTag(position);
			itemReListBtn.setTag(position);

			itemEditBtn.setOnClickListener(this);
			itemRemoveBtn.setOnClickListener(this);
			itemReListBtn.setOnClickListener(this);

			return view;
		}

		@Override
		public void onClick(View v) {
			String strTag = v.getTag().toString();
			int viewID = v.getId();
			int viewIndex = Integer.parseInt(strTag);

			switch (viewID) {
			case R.id.itemEditBtn:
				Intent intent = new Intent(mContext, EditItemActivity.class);
				intent.putExtra("itemType", ItemType.ITEM_REJECTED);
				intent.putExtra("itemPosition", viewIndex);
				startActivityForResult(intent, REQ_CODE_REJECTED);
				break;
			case R.id.itemRemoveBtn:
				removeRejectedItem(viewIndex);
				break;
			case R.id.itemReListBtn:
				relistRejectedItem(viewIndex);
				break;
			}
		}

		private void removeRejectedItem(int pos) {

			RemoveItemTask removeItemTask = new RemoveItemTask(mContext,
					setItemTaskCallBack, userId, rejectedItems.get(pos)
							.getUiId());
			removeItemTask.execute("");
		}

		private void relistRejectedItem(int pos) {
			// Start a Task for retrieving the template information
			ListItemTask listItemTask = new ListItemTask(mContext,
					setItemTaskCallBack, userId, rejectedItems.get(pos)
							.getUiId());
			listItemTask.execute("");
		}
	}

	AnyTaskCallback setItemTaskCallBack = new AnyTaskCallback() {

		@Override
		public void onResult(boolean success, String result) {
			showMessage(result, Toast.LENGTH_LONG);
		}
	};

	private void showMessage(String strMsg, int duration) {
		if (strMsg != null && !strMsg.isEmpty()) {
			Toast.makeText(mActivity, strMsg, duration).show();
		}
	}

	@Override
	public void onActivityResult(int requestCode, int resultCode, Intent data) {
		super.onActivityResult(requestCode, resultCode, data);

		if (data != null && resultCode == Activity.RESULT_OK) {
			boolean bUpdated = data.getBooleanExtra("updated", false);
			if (!bUpdated)
				return;

			if (requestCode == REQ_CODE_UNPUBED) {
				mAdapter1.notifyDataSetChanged();
			} else if (requestCode == REQ_CODE_REJECTED) {
				mAdapter2.notifyDataSetChanged();
			}
		}
	}
}
