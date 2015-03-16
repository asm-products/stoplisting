package com.listingproduct.stoplisting;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Typeface;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.view.View.OnClickListener;
import android.webkit.WebSettings;
import android.webkit.WebSettings.PluginState;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.ProgressBar;

public class AuthTokenActivity extends Activity implements
		OnClickListener {

	@SuppressWarnings("deprecation")
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.auth_token_screen);

		String authTokenUrl = "";
		Intent intent = getIntent();
		if (intent != null) {
			authTokenUrl = intent.getStringExtra("authTokenUrl");
		}
		
		if (authTokenUrl.isEmpty())
			finish();
		
		Button backBut = (Button) findViewById(R.id.backBut);
		backBut.setTypeface(Typeface.createFromAsset(getAssets(),
				"fontawesome-webfont.ttf"));
		backBut.setOnClickListener(this);

		final WebView webView = (WebView) findViewById(R.id.webView);
		final ProgressBar progBar = (ProgressBar) findViewById(R.id.progBar);
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
				progBar.setVisibility(View.GONE);
			}
		});
		
		webView.loadUrl(authTokenUrl);
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
