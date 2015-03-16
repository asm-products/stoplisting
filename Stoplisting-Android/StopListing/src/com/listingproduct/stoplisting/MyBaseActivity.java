package com.listingproduct.stoplisting;

import android.app.Activity;
import android.os.Bundle;

public class MyBaseActivity extends Activity {
	protected Application mMyApp;

	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		mMyApp = (Application) this.getApplicationContext();
	}

	protected void onResume() {
		super.onResume();
		mMyApp.setCurrentActivity(this);
	}

	protected void onPause() {
		clearReferences();
		super.onPause();
	}

	protected void onDestroy() {
		clearReferences();
		super.onDestroy();
	}

	private void clearReferences() {
		Activity currActivity = mMyApp.getCurrentActivity();
		if (currActivity != null && currActivity.equals(this))
			mMyApp.setCurrentActivity(null);
	}
}
