package com.listingproduct.stoplisting;

import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Typeface;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentTransaction;

import com.jeremyfeinstein.slidingmenu.lib.SlidingMenu;
import com.jeremyfeinstein.slidingmenu.lib.SlidingMenu.OnOpenListener;
import com.listingproduct.stoplisting.fragment.AccountFragment;
import com.listingproduct.stoplisting.fragment.DashboardFragment;
import com.listingproduct.stoplisting.fragment.ManageFragment;
import com.listingproduct.stoplisting.fragment.PromoteFragment;
import com.listingproduct.stoplisting.fragment.SwankSearchFragment;
import com.listingproduct.stoplisting.fragment.UpgradeFragment;
import com.listingproduct.stoplisting.fragment.UploadFragment;
import android.util.DisplayMetrics;
import android.view.KeyEvent;
import android.view.View;
import android.view.Window;
import android.view.View.OnClickListener;
import android.widget.ImageButton;
import android.widget.TextView;

public class MainActivity extends MyBaseFragmentActivity implements
		OnClickListener {

	public SlidingMenu menu;
	public Typeface font;

	TextView pageTitleTv;

	DashboardFragment dashboardFragment = new DashboardFragment();
	UploadFragment uploadFragment = new UploadFragment();
	PromoteFragment promoteFragment = new PromoteFragment();
	SwankSearchFragment swankFragment = new SwankSearchFragment();
	// LiveFinderFragment liveFragment = new LiveFinderFragment();
	AccountFragment accountFragment = new AccountFragment();
	UpgradeFragment upgradeFragment = new UpgradeFragment();

	private Fragment currentFragment; // holds current active fragment.

	public boolean hideEbayAuthDlgOnPubedItemPage = false;
	public boolean hideEbayAuthDlgOnManagedItempage = false;

	String Tag = "stMainActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.activity_main);

		pageTitleTv = (TextView) findViewById(R.id.pageTitle);
		ImageButton menuBtn = (ImageButton) findViewById(R.id.menuBtn);
		menuBtn.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				menu.showSecondaryMenu(true);
			}
		});

		DisplayMetrics metrics = new DisplayMetrics();
		getWindowManager().getDefaultDisplay().getMetrics(metrics);
		int left = (int) (metrics.widthPixels * 0.7);
		menu = new SlidingMenu(this);
		menu.setMode(SlidingMenu.LEFT);
		menu.setTouchModeAbove(SlidingMenu.TOUCHMODE_MARGIN);
		menu.setShadowWidth(5);
		menu.setFadeDegree(0.0f);
		menu.attachToActivity(this, SlidingMenu.SLIDING_CONTENT);
		menu.setBehindWidth(left);
		menu.setMenu(R.layout.main_menu);
		menu.setOnOpenListener(new OnOpenListener() {
			@Override
			public void onOpen() {
				showActions();
			}
		});

		// load Font
		font = ((Application) getApplication()).getFont();

		// Set first Fragment
		changeFragment("dashboardPage", "StopListing - Dashboard",
				R.id.frameRootContainer, dashboardFragment, false);
	}

	@Override
	protected void onDestroy() {
		clearBackStack();
		super.onDestroy();
	}

	public void popBackStack() {
		if (this.getSupportFragmentManager().getBackStackEntryCount() != 0) {
			this.getSupportFragmentManager().popBackStack();
		}
	}

	private void clearBackStack() {
		// FragmentManager manager = getFragmentManager();
		// if (manager.getBackStackEntryCount() > 0) {
		// manager.popBackStackImmediate(null,
		// FragmentManager.POP_BACK_STACK_INCLUSIVE);
		// }

		FragmentManager fm = getSupportFragmentManager();
		int nStacks = fm.getBackStackEntryCount();
		for (int i = 0; i < nStacks; ++i) {
			fm.popBackStackImmediate(null,
					FragmentManager.POP_BACK_STACK_INCLUSIVE);
		}
	}

	public void changeFragment(String fragmentTag, String fragmentTitle,
			int layoutId, Fragment frag, boolean addBackStack) {

		if (fragmentTitle != null) {
			pageTitleTv.setText(fragmentTitle);
		}

		System.gc();

		FragmentManager fm = getSupportFragmentManager();
		FragmentTransaction transaction = fm.beginTransaction();

		/*---------------------------------------------------------------------
							Fragment의 이전상태를 보존하려는 경우
		---------------------------------------------------------------------*/

		/*
		 * Fragment oldFrag = fm.findFragmentByTag(fragmentTag); if (oldFrag !=
		 * null && oldFrag.isVisible()) { return; }
		 * 
		 * transaction.setTransition(FragmentTransaction.TRANSIT_NONE); if
		 * (oldFrag == null) { // 이미 이Fragment가 Stack에 없었다면 if (currentFragment
		 * != null) { // 현재 능동인Fragment가 존재한다면 transaction.add(layoutId, frag,
		 * fragmentTag); transaction.show(frag);
		 * transaction.hide(currentFragment); } else { // 처음으로 기동하는 Fragment
		 * transaction.add(layoutId, frag, fragmentTag); } currentFragment =
		 * frag; } else { // 이미 이Fragment가 Stack에 쌓여있었다면
		 * transaction.show(oldFrag); transaction.hide(currentFragment);
		 * currentFragment = oldFrag; }
		 * 
		 * if (addBackStack) transaction.addToBackStack(fragmentTag);
		 * transaction.commit();
		 */

		/*---------------------------------------------------------------------
		Fragment의 이전상태를 보존하지않는 경우(Fragment의 OnCreateView()함수가 다시 호출됨.)
		---------------------------------------------------------------------*/

		// transaction.setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE);
		transaction.setTransition(FragmentTransaction.TRANSIT_FRAGMENT_OPEN);
		transaction.replace(layoutId, frag, fragmentTag);

		if (addBackStack)
			transaction.addToBackStack(null);
		transaction.commit();

	}

	private void showActions() {

		// Show UserName
		TextView userNameTv = (TextView) findViewById(R.id.userNameTv);
		userNameTv.setText(((Application) getApplication()).getUserName());

		// Show Listings Remaining
		TextView listingsRemainingTv = (TextView) findViewById(R.id.listingsRemainingTv);
		listingsRemainingTv.setText(String
				.valueOf(((Application) getApplication()).mListingsRemaining));

		TextView iconDashboard = (TextView) findViewById(R.id.iconDashboard);
		TextView iconUpload = (TextView) findViewById(R.id.iconUpload);
		TextView iconManage = (TextView) findViewById(R.id.iconManage);
		TextView iconPromote = (TextView) findViewById(R.id.iconPromote);
		TextView iconSwank = (TextView) findViewById(R.id.iconSwank);
		TextView iconLive = (TextView) findViewById(R.id.iconLive);
		TextView iconAccount = (TextView) findViewById(R.id.iconAccount);
		TextView iconUpgrade = (TextView) findViewById(R.id.iconUpgrade);
		TextView iconSupport = (TextView) findViewById(R.id.iconSupport);

		// Set icon font
		iconDashboard.setTypeface(font);
		iconUpload.setTypeface(font);
		iconManage.setTypeface(font);
		iconPromote.setTypeface(font);
		iconSwank.setTypeface(font);
		iconLive.setTypeface(font);
		iconAccount.setTypeface(font);
		iconUpgrade.setTypeface(font);
		iconSupport.setTypeface(font);

		// Get View and set onclick listener
		View actionDashboard = (View) findViewById(R.id.actionDashboard);
		View actionUpload = (View) findViewById(R.id.actionUpload);
		View actionManage = (View) findViewById(R.id.actionManage);
		View actionPromote = (View) findViewById(R.id.actionPromote);
		View actionSwank = (View) findViewById(R.id.actionSwank);
		View actionLive = (View) findViewById(R.id.actionLive);
		View actionAccount = (View) findViewById(R.id.actionAccount);
		View actionUpgrade = (View) findViewById(R.id.actionUpgrade);
		View actionContact = (View) findViewById(R.id.actionSupport);

		actionDashboard.setOnClickListener(this);
		actionUpload.setOnClickListener(this);
		actionManage.setOnClickListener(this);
		actionPromote.setOnClickListener(this);
		actionSwank.setOnClickListener(this);
		actionLive.setOnClickListener(this);
		actionAccount.setOnClickListener(this);
		actionUpgrade.setOnClickListener(this);
		actionContact.setOnClickListener(this);
	}

	@Override
	public void onClick(View v) {
		int id = v.getId();

		if (id == R.id.actionDashboard) {
			// Dashboard Action
			changeFragment("dashboardPage", "StopListing - Dashboard",
					R.id.frameRootContainer, dashboardFragment, false);
		} else if (id == R.id.actionUpload) {
			// Upload Action
			changeFragment("uploadPage", "StopListing - Upload",
					R.id.frameRootContainer, uploadFragment, false);
		} else if (id == R.id.actionManage) {
			// Manage Action
			changeFragment("managePage", "StopListing - Manage",
					R.id.frameRootContainer, new ManageFragment(0), false);
		} else if (id == R.id.actionPromote) {
			// Promote Action
			changeFragment("promotePage", "StopListing - Promote",
					R.id.frameRootContainer, promoteFragment, false);
		} else if (id == R.id.actionSwank) {
			// Swank Action
			changeFragment("swankPage", "StopListing - Swank Search",
					R.id.frameRootContainer, swankFragment, false);
		} else if (id == R.id.actionLive) {
			// Live Action
			// changeFragment("livePage", "StopListing - Live Finder",
			// R.id.frameRootContainer, liveFragment, false);
			startActivity(new Intent(this, LiveFinderActivity.class));
		} else if (id == R.id.actionAccount) {
			// Account Action
			changeFragment("accountPage", "StopListing - Account",
					R.id.frameRootContainer, accountFragment, false);
		} else if (id == R.id.actionUpgrade) {
			// Upgrade Action
			changeFragment("upgradePage", "StopListing - Upgrade",
					R.id.frameRootContainer, upgradeFragment, false);
		} else if (id == R.id.actionSupport) {
			// Support Action
			Intent intent = new Intent(Intent.ACTION_VIEW,
					Uri.parse("https://app.purechat.com/w/StopListing"));
			startActivity(intent);
			// Intent intent = new Intent(MainActivity.this,
			// SupportActivity.class);
			// startActivity(intent);
		}

		// Hide Action Menu
		// Next show mode is not a Activity mode, Close the menu panel.
		if (id != R.id.actionLive && id != R.id.actionSupport) {
			menu.showContent(true);
		}
	}

	// called from Dashboard fragment
	public void showWeekItemPage() {

	}

	public void showRejectPage() {
		// Manage Page Reject Tab
		changeFragment("managePage", "StopListing - Manage",
				R.id.frameRootContainer, new ManageFragment(1), false);
	}

	public void showAllItemsPage() {
		// Manage Page Reject Tab
		changeFragment("managePage", "StopListing - Manage",
				R.id.frameRootContainer, new ManageFragment(0), false);
	}

	public void showPlanUpdatePage() {
		// Upgrade Action
		changeFragment("upgradePage", "StopListing - Upgrade",
				R.id.frameRootContainer, upgradeFragment, false);
	}

	public void logOut() {

		// reset user login Info
		SharedPreferences pref = getSharedPreferences("UserInfo", MODE_PRIVATE);
		SharedPreferences.Editor editor = pref.edit();
		editor.putBoolean("saveInfo", false);
		editor.putString("email", "");
		editor.putString("password", "");
		editor.commit();

		Intent intent = new Intent(MainActivity.this, LoginActivity.class);
		startActivity(intent);
		finish();
	}

	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		if (keyCode == KeyEvent.KEYCODE_BACK) {
			if (menu.isMenuShowing()) {
				menu.showContent(true);
				return true;
			}
			if (event.getRepeatCount() == 0) {
				/*
				 * if (this.getSupportFragmentManager().getBackStackEntryCount()
				 * != 0) { this.getSupportFragmentManager().popBackStack();
				 * return true; }
				 */
				popBackStack();
				if (!dashboardFragment.isVisible()) {
					// Dashboard Action
					changeFragment("dashboardPage", "StopListing - Dashboard",
							R.id.frameRootContainer, dashboardFragment, false);
					return true;
				} else if(!dashboardFragment.isMainTabOpened()) {
					dashboardFragment.openMainTab();
					return true;
				}
			}
		}

		return super.onKeyDown(keyCode, event);
	}
}

// F:\Work_Data\Data\android_Doc\Tech&Tip\안드로이드 UISamples\2. ViewPager &
// ATabbar\2.
// Android-ViewPagerIndicator\Android-ViewPagerIndicator-master\Android-ViewPagerIndicator-master\library