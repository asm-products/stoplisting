package com.listingproduct.stoplisting;

import com.listingproduct.stoplisting.global.GlobalDefine;
import com.listingproduct.stoplisting.task.GetPlanListTask;
import com.listingproduct.stoplisting.utilities.NetStateUtilities;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.view.KeyEvent;

public class SplashActivity extends Activity {

	private boolean isRunning;
	String Tag = "stWelcomeActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
	
		setContentView(R.layout.splash_screen);
		this.isRunning = true;

		// Check network connection
		if (!NetStateUtilities.hasConnection(this)) {

			AlertDialog.Builder builder = new AlertDialog.Builder(this); 

			// set property
			builder.setTitle("Notice")
					.setMessage("Cannot connect Server. No network connection.")
					.setPositiveButton("Ok",
							new DialogInterface.OnClickListener() {
								public void onClick(DialogInterface dialog,
										int whichButton) {
									dialog.dismiss();
									finish();
								}
							});
			// Show AlertDialog
			AlertDialog dialog = builder.create();
			dialog.show(); 
			return;
		} else {
			
			// Start a task for getting the Plan List
			GetPlanListTask getPlansTask = new GetPlanListTask(this, null);
			getPlansTask.execute(GlobalDefine.SiteURLs.plansURL);
			
			// start Splash Action 
			startSplash();
		}		
	}

	private void startSplash() {
		Thread background = new Thread() {
			public void run() {
				try {
					// Thread will sleep for 3 seconds
					sleep(2 * 1000);
					return;
				} catch (Exception e) {
					return;
				} finally {
					runOnUiThread(new Runnable() {
						public void run() {
							doFinish();
						}
					});
				}
			}
		};
		// start thread
		background.start();
	}

	private void doFinish() {
		if (this.isRunning) {
			this.isRunning = false;
			startActivity(new Intent(SplashActivity.this, LoginActivity.class));
			// Remove activity
			finish();
		}
	}

	@Override
	protected void onResume() {
		super.onResume();
		/*View decorView = getWindow().getDecorView();
		decorView.setSystemUiVisibility(View.SYSTEM_UI_FLAG_LAYOUT_STABLE
				| View.SYSTEM_UI_FLAG_LAYOUT_HIDE_NAVIGATION
				| View.SYSTEM_UI_FLAG_LAYOUT_FULLSCREEN
				| View.SYSTEM_UI_FLAG_HIDE_NAVIGATION
				| View.SYSTEM_UI_FLAG_FULLSCREEN);
				*/
	}

	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		if (keyCode == KeyEvent.KEYCODE_BACK) {
			this.isRunning = false;
			finish();
			return true;
		}
		return super.onKeyDown(keyCode, event);
	}
}
