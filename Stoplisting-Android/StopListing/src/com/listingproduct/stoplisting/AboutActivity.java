package com.listingproduct.stoplisting;

import android.app.Activity;
import android.graphics.Typeface;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebSettings.PluginState;
import android.widget.Button;

public class AboutActivity extends MyBaseActivity {

	String Tag = "stAboutActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.about_screen);
		
		Button backBut = (Button)findViewById(R.id.backBtn);
		backBut.setTypeface(((Application) getApplication()).getFont());
		backBut.setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View v) {
				finish();
			}
		});
		
		WebView aboutWeb = (WebView) findViewById(R.id.aboutWeb);
		
		aboutWeb.getSettings().setJavaScriptEnabled(true);
		aboutWeb.getSettings().setDomStorageEnabled(true);
		aboutWeb.getSettings().setBuiltInZoomControls(false);
		aboutWeb.getSettings().setLoadWithOverviewMode(true);
		aboutWeb.setInitialScale(100);
		aboutWeb.getSettings().setUseWideViewPort(true);
		aboutWeb.getSettings().setLoadsImagesAutomatically(true);		
		aboutWeb.getSettings().setAllowFileAccess(true);
		aboutWeb.getSettings().setSupportZoom(true);
		aboutWeb.getSettings().setAppCacheEnabled(true);
		aboutWeb.getSettings().setAllowContentAccess(true);
		aboutWeb.getSettings().setCacheMode(WebSettings.LOAD_NO_CACHE);
		aboutWeb.getSettings().setPluginState(PluginState.ON);
		aboutWeb.setScrollBarStyle(WebView.SCROLLBARS_OUTSIDE_OVERLAY);
		aboutWeb.getSettings().setUserAgentString(
				"Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.63 Safari/537.31");
		aboutWeb.loadUrl("file:///android_asset/local/about_swank_notes.html");
	}
}
