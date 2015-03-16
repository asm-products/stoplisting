package com.listingproduct.stoplisting.fragment;

import java.util.ArrayList;

import com.listingproduct.stoplisting.MainActivity;
import com.listingproduct.stoplisting.R;

import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;

import com.listingproduct.stoplisting.data.StopListingItem;

public class DashboardFragment1 extends Fragment implements OnClickListener {

	TextView leftTabTv;
	TextView rightTabTv;

	MainActivity mActivity;

	View page1;
	View page2;
	ListView itemList;

	ArrayList<StopListingItem> publishedItems = new ArrayList<StopListingItem>();

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {

		mActivity = (MainActivity) getActivity();
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

		// For page2/published items
		itemList = (ListView) view.findViewById(R.id.itemList);

		selectTab(0);
		return view;
	}

	@Override
	public void onViewCreated(View view, Bundle savedInstanceState) {
		super.onViewCreated(view, savedInstanceState);
		getPublishItems();
		PublishItemsAdapter mAdapter = new PublishItemsAdapter(getActivity(),
				publishedItems);
		itemList.setAdapter(mAdapter);
	}

	private void getPublishItems() {
		publishedItems.clear();
		publishedItems
				.add(new StopListingItem(
						"BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa",
						200, "07/05/15", "New", "Fixed Price", 7, 0x7,
						"Minimal Template #1"));
		publishedItems
				.add(new StopListingItem(
						"BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa",
						150, "06/05/15", "New", "Fixed Price", 3, 0x1,
						"Minimal Template #2"));
		publishedItems
				.add(new StopListingItem(
						"BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa",
						250, "05/05/15", "New", "Fixed Price", 1, 0x3,
						"Minimal Template #3"));
		publishedItems
				.add(new StopListingItem(
						"BLACKBERRY PINK SMARTPHONE WITH CARRYING CASE", "aaa",
						300, "04/05/15", "New", "Fixed Price", 7, 0x2,
						"Minimal Template #4"));
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
			mActivity.showWeekItemPage();
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
	}

	public static class PublishItemsAdapter extends BaseAdapter implements
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
						R.layout.published_listitem, null);
			}

			// set data
			StopListingItem curItem = getItem(position);
			TextView itemPriceTv = (TextView) view
					.findViewById(R.id.itemPriceTv);
			TextView itemDateTv = (TextView) view.findViewById(R.id.itemDateTv);
			TextView itemStateTv = (TextView) view
					.findViewById(R.id.itemStateTv);
			TextView itemFormatTv = (TextView) view
					.findViewById(R.id.itemFormatTv);
			TextView itemDurationTv = (TextView) view
					.findViewById(R.id.itemDurationTv);
			TextView itemPromotedTv = (TextView) view
					.findViewById(R.id.itemPromoteTv);
			itemPromotedTv.setTypeface(((MainActivity) mContext).font);
			TextView itemTemplateTv = (TextView) view
					.findViewById(R.id.itemTemplateTv);

			TextView itemEditBtn = (TextView) view
					.findViewById(R.id.itemEditBtn);
			TextView itemRemoveBtn = (TextView) view
					.findViewById(R.id.itemRemoveBtn);
			TextView itemCheckBtn = (TextView) view
					.findViewById(R.id.itemCheckBtn);

			// set Tag for event processing and onclickListener
			itemEditBtn.setTag(position);
			itemRemoveBtn.setTag(position);
			itemCheckBtn.setTag(position);
			itemEditBtn.setTypeface(((MainActivity) mContext).font);
			itemRemoveBtn.setTypeface(((MainActivity) mContext).font);
			itemCheckBtn.setTypeface(((MainActivity) mContext).font);

			itemEditBtn.setOnClickListener(this);
			itemRemoveBtn.setOnClickListener(this);
			itemCheckBtn.setOnClickListener(this);

			// Set Plan Title
			itemPriceTv.setText(String.valueOf(curItem.getPrice())); // Price
			itemDateTv.setText(curItem.getDate()); // Date
			itemStateTv.setText(curItem.getState()); // State
			itemFormatTv.setText(curItem.getFormat()); // Format
			itemDurationTv.setText(String.valueOf(curItem.getDuration())); // Duration
			itemTemplateTv.setText(curItem.getTemplate()); // Template
			int nPromotedVal = curItem.getPrompted();
			String strPromoted = "";
			if ((nPromotedVal & 0x0001) > 0) { // facebook
				strPromoted += mContext.getString(R.string.title_facebook)
						+ " ";
			}
			if ((nPromotedVal & 0x0002) > 0) { // twitter
				strPromoted += mContext.getString(R.string.title_twitter) + " ";
			}
			if ((nPromotedVal & 0x0004) > 0) { // google+
				strPromoted += mContext.getString(R.string.title_google);
			}

			itemPromotedTv.setText(strPromoted); // Template
			return view;
		}

		@Override
		public void onClick(View v) {
			String strTag = v.getTag().toString();
			int viewID = v.getId();
			int viewIndex = Integer.parseInt(strTag);

			switch (viewID) {
			case R.id.itemEditBtn:
				break;
			case R.id.itemRemoveBtn:
				break;
			case R.id.itemCheckBtn:
				break;
			}
		}
	}
}
