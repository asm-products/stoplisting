package com.listingproduct.stoplisting.fragment;

import java.util.ArrayList;

import com.listingproduct.stoplisting.Application;
import com.listingproduct.stoplisting.R;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.GridView;
import android.widget.TextView;

import com.listingproduct.stoplisting.data.Plan;
import com.listingproduct.stoplisting.global.GlobalDefine;
import com.listingproduct.stoplisting.task.GetPlanListTask;
import com.listingproduct.stoplisting.task.GetPlanListTaskCallback;
import com.listingproduct.stoplisting.utilities.NetStateUtilities;

public class UpgradeFragment extends Fragment implements
		GetPlanListTaskCallback {

	Application mApplication;

	GridView upgradeItemGv;
	PlanAdapter mAdapter;

	ArrayList<Plan> planLists;

	private ProgressDialog mDlg;

	String Tag = "UpgradeFragment";

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {

		mApplication = (Application) getActivity().getApplication();
		View view = inflater.inflate(R.layout.upgrade_screen, container, false);
		upgradeItemGv = (GridView) view.findViewById(R.id.upgradeItemGv);
		return view;
	}

	@Override
	public void onViewCreated(View view, Bundle savedInstanceState) {
		super.onViewCreated(view, savedInstanceState);
		getPlanListData();
	}

	private void getPlanListData() {

		planLists = mApplication.getPlanList();
		if (planLists != null && planLists.size() > 0) {
			mAdapter = new PlanAdapter(getActivity(), planLists);
			upgradeItemGv.setAdapter(mAdapter);
		} else {
			// Network state check
			if (!NetStateUtilities.hasConnection(getActivity())) {

				// clear event list items
				mAdapter.notifyDataSetChanged();

				AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());

				// set property
				builder.setTitle("Notice")
						.setMessage("Cannot connect Server. No network connection.")
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
			
			mDlg = new ProgressDialog(getActivity());
			mDlg.setProgressStyle(ProgressDialog.STYLE_SPINNER);
			mDlg.setTitle("Please wait.");
			mDlg.setMessage("Loading Plans data...");
			mDlg.setCanceledOnTouchOutside(false);
			mDlg.setCancelable(false);
			mDlg.show();
			
			// Start a task for getting the Plan List
			GetPlanListTask getPlansTask = new GetPlanListTask(getActivity(),
					this);
			getPlansTask.execute(GlobalDefine.SiteURLs.plansURL);
		}
	}

	public static class PlanAdapter extends BaseAdapter implements
			OnClickListener {

		Context mContext;
		ArrayList<Plan> planList;

		public PlanAdapter(Context context, ArrayList<Plan> list) {

			mContext = context;
			planList = list;
		}

		@Override
		public int getCount() {

			return planList.size();
		}

		@Override
		public long getItemId(int position) {
			return position;
		}

		@Override
		public Plan getItem(int position) {
			return planList.get(position);
		}

		@Override
		public View getView(int position, View convertView, ViewGroup parent) {

			View view = convertView;
			if (convertView == null) {
				view = LayoutInflater.from(mContext).inflate(
						R.layout.upgrade_griditem, null);
			}

			// set data
			Plan curPlan = getItem(position);
			TextView planTitleTv = (TextView) view.findViewById(R.id.planTitle);
			TextView planCostsTv = (TextView) view
					.findViewById(R.id.planCostValueTv);
			TextView planDescTv = (TextView) view
					.findViewById(R.id.planDescription);
			Button planUpgradebtn = (Button) view.findViewById(R.id.upgradeBtn);

			// set Tag for event processing and onclickListener
			planUpgradebtn.setOnClickListener(this);
			planUpgradebtn.setTag(position);

			// Set Plan Title
			planTitleTv.setText(curPlan.getPlanTitle());

			// Set Plan Costs
			planCostsTv.setText(String.valueOf(curPlan.getPlanPrice()));

			// Set Plan Listing Description
			String strPlanListingDesc = String.format(
					mContext.getString(R.string.desc_plain_format),
					curPlan.getPlanListingCount());
			planDescTv.setText(strPlanListingDesc);
			return view;
		}

		@Override
		public void onClick(View v) {
			String strTag = v.getTag().toString();
			int viewIndex = Integer.parseInt(strTag);
		}
	}

	@Override
	public void onResult(boolean result, String errorMsg) {
		// Hide Progress dialog
		if (mDlg != null && mDlg.isShowing()) {
			mDlg.dismiss();
		}
		
		if (result) {
			planLists = ((Application) (getActivity().getApplication()))
					.getPlanList();
			mAdapter = new PlanAdapter(getActivity(), planLists);
			upgradeItemGv.setAdapter(mAdapter);			
		} else {
			AlertDialog.Builder builder = new AlertDialog.Builder(getActivity()); 	
			if (errorMsg == null || errorMsg.isEmpty())
				errorMsg = "Sorry Can't fetch Plans data from server. Please try again.";
			// set property
			builder.setTitle("Notice")
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
}
