package com.listingproduct.stoplisting;

import android.app.Activity;
import android.graphics.Typeface;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.view.View.OnClickListener;
import android.webkit.WebSettings;
import android.webkit.WebViewClient;
import android.webkit.WebSettings.PluginState;
import android.webkit.WebView;
import android.widget.Button;

public class WListingPreferenceActivity extends Activity implements
		OnClickListener {

	String strEmail;
	int userId;

	@SuppressWarnings("deprecation")
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.wlisting_preference_screen);

		strEmail = ((Application) getApplication()).getEmail();
		userId = ((Application) getApplication()).getUserId();
		
		Button backBut = (Button) findViewById(R.id.backBut);
		backBut.setTypeface(Typeface.createFromAsset(getAssets(),
				"fontawesome-webfont.ttf"));
		backBut.setOnClickListener(this);

		final WebView webView = (WebView) findViewById(R.id.webView);

		webView.getSettings().setJavaScriptEnabled(true);
		webView.getSettings().setDomStorageEnabled(true);
		webView.getSettings().setBuiltInZoomControls(false);
		webView.getSettings().setLoadWithOverviewMode(true);
		webView.setInitialScale(100);
		webView.getSettings().setUseWideViewPort(true);
		webView.getSettings().setLoadsImagesAutomatically(true);
		webView.getSettings().setAllowFileAccess(true);
		webView.getSettings().setSupportZoom(true);
		webView.getSettings().setAppCacheEnabled(true);
		webView.getSettings().setAllowContentAccess(true);
		webView.getSettings().setCacheMode(WebSettings.LOAD_NO_CACHE);
		webView.getSettings().setPluginState(PluginState.ON);
		webView.setScrollBarStyle(WebView.SCROLLBARS_OUTSIDE_OVERLAY);
		webView
				.getSettings()
				.setUserAgentString(
						"Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.63 Safari/537.31");
		
		webView.setWebViewClient(new WebViewClient() {

			@Override
			public void onPageFinished(WebView view, String url) {
				/*
				 * reportWeb
				 * .loadUrl("javascript:(function() { setParameters(2,3)})()");
				 */
				String strParam = String.format(
						"javascript:(function() { App.load('%d','%s'); })()",
						userId, strEmail);
				webView.loadUrl(strParam);
			}
		});
		
		webView
				.loadUrl("file:///android_asset/local/listing_preference.html");
	}

	@Override
	public void onClick(View view) {
		switch (view.getId()) {
		case R.id.backBut:
			finish();
			break;
		default:
			break;
		}
	}
}
