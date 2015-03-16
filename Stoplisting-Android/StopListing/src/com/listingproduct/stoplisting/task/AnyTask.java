package com.listingproduct.stoplisting.task;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.URI;
import java.net.URISyntaxException;
import java.util.ArrayList;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.CoreProtocolPNames;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.PopupWindow;

import com.listingproduct.stoplisting.Application;
import com.listingproduct.stoplisting.LiveFinderResultActivity;
import com.listingproduct.stoplisting.R;
import com.listingproduct.stoplisting.data.Plan;
import com.listingproduct.stoplisting.global.GlobalFunc;

//AsyncTask<Params, Progress, Result>
public class AnyTask extends AsyncTask<String, Void, String> {

	private Context mContext;
	private Application mApplication;
	private AnyTaskCallback mCallback;

	private ProgressDialog mDlg;
	private String mProgTitle;
	private String mProgMsg;

	private String mErrorMsg;
	private boolean mSuccess;
	
	private String Tag = "AnyTask";

	public AnyTask(Context context, AnyTaskCallback callback, String progTitle,
			String progMsg) {
		mContext = context;
		mCallback = callback;
		mProgTitle = progTitle;
		mProgMsg = progMsg;
	}

	@Override
	protected void onPreExecute() {
		super.onPreExecute();
		// Show Progress Dialog
		if (mProgTitle != null && !mProgTitle.isEmpty() && mProgTitle != null
				&& !mProgTitle.isEmpty()) {

			mDlg = new ProgressDialog(mContext);
			mDlg.setProgressStyle(ProgressDialog.STYLE_SPINNER);
			mDlg.setTitle(mProgTitle);
			mDlg.setMessage(mProgMsg);
			mDlg.setCanceledOnTouchOutside(false);
			mDlg.setCancelable(false);
			mDlg.show();
		}
		
		mErrorMsg = "";
		mSuccess = true;
	}

	@Override
	protected String doInBackground(String... urls) {

		// This method for HttpConnection
		BufferedReader bufferedReader = null;
		try {
			HttpGet request = new HttpGet();
			request.setHeader("Content-Type", "text/plain; charset=utf-8");
			request.setURI(new URI(urls[0]));

			// Set Timeout Parameter
			HttpParams httpPrameters = new BasicHttpParams();
			HttpConnectionParams.setConnectionTimeout(httpPrameters, 10000);
			HttpConnectionParams.setSoTimeout(httpPrameters, 10000);
			request.setParams(httpPrameters);

			HttpClient client = new DefaultHttpClient();
			client.getParams().setParameter(CoreProtocolPNames.USER_AGENT,
					"android");

			HttpResponse response = client.execute(request);
			bufferedReader = new BufferedReader(new InputStreamReader(response
					.getEntity().getContent()));

			StringBuffer stringBuffer = new StringBuffer("");
			String line = "";

			String NL = System.getProperty("line.separator");
			while ((line = bufferedReader.readLine()) != null) {
				stringBuffer.append(line + NL);
			}
			bufferedReader.close();
			String page = stringBuffer.toString();
			mSuccess = true;
			return page;
		} catch (URISyntaxException | IOException e) {
			mSuccess = false;
			mErrorMsg = e.getMessage();
			GlobalFunc.viewLog(Tag, "Error occured in reading the plan data.",
					true);
			GlobalFunc.viewLog(Tag, e.toString(), true);
			return "";
		} finally {
			if (bufferedReader != null) {
				try {
					bufferedReader.close();
				} catch (IOException e) {
					GlobalFunc.viewLog(Tag, e.toString(), true);
				}
			}
		}
	}

	@Override
	protected void onPostExecute(String result) {
		if (mDlg != null && mDlg.isShowing())
			mDlg.dismiss();
		if (mCallback != null) {
			if (mSuccess) {
				mCallback.onResult(true, result);
			} else {
				mCallback.onResult(false, mErrorMsg);
			}
		}
	}

	@Override
	protected void onCancelled() {
		super.onCancelled();
	}
}
