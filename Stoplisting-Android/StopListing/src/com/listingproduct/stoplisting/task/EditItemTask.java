package com.listingproduct.stoplisting.task;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.URI;
import java.net.URISyntaxException;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.utils.URLEncodedUtils;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.CoreProtocolPNames;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.ProgressDialog;
import android.content.Context;
import android.os.AsyncTask;
import com.listingproduct.stoplisting.data.StopListingItem;
import com.listingproduct.stoplisting.global.GlobalDefine;
import com.listingproduct.stoplisting.global.GlobalFunc;

//AsyncTask<Params, Progress, Result>
public class EditItemTask extends AsyncTask<String, Void, String> {

	private Context mContext;
	private AnyTaskCallback mCallback;
	private String mUrl;

	private ProgressDialog mDlg;
	private String mProgTitle;
	private String mProgMsg;

	private String mErrorMsg;
	private boolean mSuccess;

	private String Tag = "AnyTask";

	public EditItemTask(Context context, AnyTaskCallback callback, int userId,
			StopListingItem item) {
		mContext = context;
		mCallback = callback;
		mProgTitle = "Please wait";
		mProgMsg = "Updating Item...";

		// Start a Task for retrieving the template information
		List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(8);
		nameValuePairs.add(new BasicNameValuePair("user_id", String
				.valueOf(userId)));
		nameValuePairs.add(new BasicNameValuePair("item_id", item.getUiId()));
		nameValuePairs.add(new BasicNameValuePair("field", "edit"));
		nameValuePairs.add(new BasicNameValuePair("desc", "description"));
		nameValuePairs.add(new BasicNameValuePair("title", item.getTitle()));
		nameValuePairs.add(new BasicNameValuePair("cat", "category"));
		nameValuePairs.add(new BasicNameValuePair("price", String.valueOf(item.getPrice())));
		nameValuePairs.add(new BasicNameValuePair("ship", "shiping_details"));
		
		String paramsString = URLEncodedUtils.format(nameValuePairs, "UTF-8");
		mUrl = GlobalDefine.SiteURLs.setItem + paramsString;
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
			request.setURI(new URI(mUrl));

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
				result = result.trim();
				String strMessage;
				try {
					JSONObject jsonResult = new JSONObject(result);
					String strStatus = jsonResult.getString("status");
					if (strStatus.equals("200")) {
						strMessage = "Successfully Updated Item.";
					} else {
						strMessage = "Item Updating Failure.";
					}
				} catch (JSONException e) {
					strMessage = e.getMessage();
					e.printStackTrace();
				}
				mCallback.onResult(true, strMessage);
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
