package com.listingproduct.stoplisting.fragment;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.client.utils.URLEncodedUtils;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.listingproduct.stoplisting.AccountSyncActivity;
import com.listingproduct.stoplisting.Application;
import com.listingproduct.stoplisting.EditItemActivity;
import com.listingproduct.stoplisting.ItemType;
import com.listingproduct.stoplisting.MainActivity;
import com.listingproduct.stoplisting.R;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.view.View.OnClickListener;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.CompoundButton.OnCheckedChangeListener;

import com.listingproduct.stoplisting.data.StopListingItem;
import com.listingproduct.stoplisting.global.GlobalDefine;
import com.listingproduct.stoplisting.task.AnyTask;
import com.listingproduct.stoplisting.task.AnyTaskCallback;
import com.listingproduct.stoplisting.task.ListItemTask;
import com.listingproduct.stoplisting.task.ReListItemTask;
import com.listingproduct.stoplisting.task.RemoveItemTask;
import com.nostra13.universalimageloader.core.DisplayImageOptions;
import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.assist.FailReason;
import com.nostra13.universalimageloader.core.listener.ImageLoadingListener;

public class DashboardFragment extends Fragment implements OnClickListener {

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

	int	 tabIndex = -1;
	
	ListView pubedItemLv;
	TextView noPubedItemTv;

	ArrayList<StopListingItem> publishedItems;
	PublishItemsAdapter mAdapter;

	private static final int REQ_CODE_PUBED = 100;

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

		View view = inflater.inflate(R.layout.dashboard_screen, container,
				false);

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
		
		// For link buttons
		LinearLayout toWeekLL = (LinearLayout) view.findViewById(R.id.toWeekLL);
		LinearLayout toMangeLL = (LinearLayout) view
				.findViewById(R.id.toManageLL);
		LinearLayout toRejectedLL = (LinearLayout) view
				.findViewById(R.id.toRejectedLL);
		LinearLayout toHelpLL = (LinearLayout) view.findViewById(R.id.toHelpLL);

		toWeekLL.setOnClickListener(this);
		toMangeLL.setOnClickListener(this);
		toRejectedLL.setOnClickListener(this);
		toHelpLL.setOnClickListener(this);

		TextView weekTotalTv = (TextView) view.findViewById(R.id.weekTotalTv);
		TextView readyPublishTv = (TextView) view
				.findViewById(R.id.readyPublishTv);
		TextView needMoreTv = (TextView) view.findViewById(R.id.needMoreTv);

		Application application = (Application) getActivity().getApplication();
		weekTotalTv.setText(String.valueOf(application.mWeekTotal));
		readyPublishTv.setText(String.valueOf(application.mReadyPublish));
		needMoreTv.setText(String.valueOf(application.mNeedMore));

		// For page2/published items
		pubedItemLv = (ListView) view.findViewById(R.id.itemList);
		noPubedItemTv = (TextView) view.findViewById(R.id.noPubedItemTv);

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
				&& !mActivity.hideEbayAuthDlgOnPubedItemPage) {
			page3.setVisibility(View.VISIBLE);
		} else {
			page3.setVisibility(View.GONE);
		}
		
		selectTab(0);
		return view;
	}

	@Override
	public void onViewCreated(View view, Bundle savedInstanceState) {
		super.onViewCreated(view, savedInstanceState);
		getPublishItems();
		mAdapter = new PublishItemsAdapter(getActivity(), publishedItems);
		pubedItemLv.setAdapter(mAdapter);

		if (publishedItems.size() == 0)
			noPubedItemTv.setVisibility(View.VISIBLE);
		else
			noPubedItemTv.setVisibility(View.GONE);
	}

	private void getPublishItems() {
		/*
		 * publishedItems.clear(); publishedItems .add(new StopListingItem(
		 * "BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa", 200,
		 * "07/05/15", "New", "Fixed Price", 7, 0x7, "Minimal Template #1"));
		 * publishedItems .add(new StopListingItem(
		 * "BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa", 150,
		 * "06/05/15", "New", "Fixed Price", 3, 0x1, "Minimal Template #2"));
		 * publishedItems .add(new StopListingItem(
		 * "BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa", 250,
		 * "05/05/15", "New", "Fixed Price", 1, 0x3, "Minimal Template #3"));
		 * publishedItems .add(new StopListingItem(
		 * "BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa", 300,
		 * "04/05/15", "New", "Fixed Price", 7, 0x2, "Minimal Template #4"));
		 */
		publishedItems = mApp.mPublishedItems;

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

		case R.id.toWeekLL:
			selectTab(1);
			break;
		case R.id.toManageLL:
			mActivity.showAllItemsPage();
			break;
		case R.id.toRejectedLL:
			mActivity.showRejectPage();
			break;
		case R.id.toHelpLL:
			// Support Action
			Intent intent = new Intent(Intent.ACTION_VIEW,
					Uri.parse("https://app.purechat.com/w/StopListing"));
			startActivity(intent);
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

		tabIndex = nIndex;
		/*if (nIndex == 1 && !mApp.getTokenStatus()
				&& !mActivity.hideEbayAuthDlgOnPubedItemPage) {
			showEbayAuthTokenDlg();
		}*/
	}

	public boolean isMainTabOpened() {
		if (tabIndex == 0)
			return true;
		else 
			return false;
	}
	
	public void openMainTab() {
		selectTab(0);
	}
	
	private void showEbayAuthTokenDlg() {
		AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(
				mActivity);
		alertDialogBuilder.setTitle("Set Ebay Auth Token");

		// make custom layout
		final CheckBox check = new CheckBox(mActivity);
		check.setText("Don't show again on this page.");
		check.setChecked(false);
		check.setOnCheckedChangeListener(new OnCheckedChangeListener() {
			public void onCheckedChanged(CompoundButton buttonView,
					boolean isChecked) {
				mActivity.hideEbayAuthDlgOnPubedItemPage = isChecked;
			}
		});

		LinearLayout layout = new LinearLayout(mActivity);
		layout.setOrientation(LinearLayout.VERTICAL);
		LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(
				LinearLayout.LayoutParams.FILL_PARENT,
				LinearLayout.LayoutParams.WRAP_CONTENT);
		params.setMargins(30, 0, 30, 0);
		layout.addView(check, params);

		// add custom layout to dialog
		alertDialogBuilder.setView(layout);
		alertDialogBuilder
				.setMessage(
						"You have not yet authorized our app to access your ebay account for listing new items. \nWould You Like to set it now?")
				.setCancelable(false)
				.setPositiveButton("Set Now",
						new DialogInterface.OnClickListener() {
							public void onClick(DialogInterface dialog, int id) {
								dialog.cancel();
								// Open AccountSync Screen
								Intent intent = new Intent(mActivity,
										AccountSyncActivity.class);
								startActivity(intent);
							}
						})
				.setNegativeButton("No Thanks",
						new DialogInterface.OnClickListener() {
							public void onClick(DialogInterface dialog, int id) {
								dialog.cancel();
							}
						});
		AlertDialog alertDialog = alertDialogBuilder.create();
		alertDialog.show();
	}

	public class PublishItemsAdapter extends BaseAdapter implements
			OnClickListener {

		Context mContext;
		ArrayList<StopListingItem> itemLists;

		public PublishItemsAdapter(Context context,
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
						R.layout.dashboard_listitem, null);
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
				intent.putExtra("itemType", ItemType.ITEM_PUBLISHED);
				intent.putExtra("itemPosition", viewIndex);
				startActivityForResult(intent, REQ_CODE_PUBED);
				break;
			case R.id.itemRemoveBtn:
				removePubedItem(viewIndex);
				break;
			case R.id.itemReListBtn:
				reListPubedItem(viewIndex);
				break;
			}
		}

		private void removePubedItem(int pos) {

			RemoveItemTask removeItemTask = new RemoveItemTask(mContext,
					setItemTaskCallBack, userId, publishedItems.get(pos)
							.getUiId());
			removeItemTask.execute("");
		}

		private void reListPubedItem(int pos) {
			// Start a Task for retrieving the template information
			ReListItemTask relistItemTask = new ReListItemTask(mContext,
					setItemTaskCallBack, userId, publishedItems.get(pos)
							.getUiId());
			relistItemTask.execute("");
		}

		AnyTaskCallback setItemTaskCallBack = new AnyTaskCallback() {

			@Override
			public void onResult(boolean success, String result) {
				showMessage(result, Toast.LENGTH_LONG);
			}
		};
	}

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
			if (bUpdated)
				mAdapter.notifyDataSetChanged();
		}
	}
}
