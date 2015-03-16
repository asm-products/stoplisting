package com.listingproduct.stoplisting;

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
import org.apache.http.params.CoreProtocolPNames;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.listingproduct.stoplisting.data.SwankItem;
import com.listingproduct.stoplisting.global.GlobalFunc;
import com.listingproduct.stoplisting.utilities.NetStateUtilities;
import com.nostra13.universalimageloader.core.DisplayImageOptions;
import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.assist.FailReason;
import com.nostra13.universalimageloader.core.listener.ImageLoadingListener;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Typeface;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Parcelable;
import android.support.v4.view.PagerAdapter;
import android.support.v4.view.ViewPager;
import android.support.v4.view.ViewPager.OnPageChangeListener;
import android.view.LayoutInflater;
import android.view.View;
import android.view.Window;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

public class SwankSearchResultActivity extends MyBaseActivity implements OnClickListener {

	private final int REG_USER_RESULT = 100;

	SwankSearchTask mSearchTask;

	String mSwankScore = "";
	String mAvgPrice = "";
	String mOverDays = "";
	
	String mSearchNote = "";

	ArrayList<SwankItem> searchItems = new ArrayList<SwankItem>();

	private View mNoResultShowPanel;
	private View mSearchContentPanel;
	
	private View mSwankResultPanel;	
	private TextView mNoteTv;
	private View mNotePanel;
	
	private TextView mSwankScoreTv;
	private TextView mAvgPriceTv;
	private TextView mOverDaysTv;
	private ViewPager mViewPager;

	Button pageNumber;

	ImageLoader imgLoader;
	DisplayImageOptions options;
	
	Typeface font;
	String Tag = "stSwankResultActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.swank_result_screen);

		imgLoader = ((Application) getApplication()).getImageLoader();
		font = ((Application) getApplication()).getFont();
		// For Image Loader
		options = new DisplayImageOptions.Builder()
				.showImageOnLoading(R.drawable.placeholder)
				.showImageForEmptyUri(R.drawable.placeholder)
				.showImageOnFail(R.drawable.placeholder).cacheInMemory(true)
				.cacheOnDisk(true).considerExifParams(true)
				.bitmapConfig(Bitmap.Config.RGB_565).build();

		Button backBtn = (Button) findViewById(R.id.backBtn);
		backBtn.setTypeface(font);
		backBtn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				finish();
			}
		});

		mNoResultShowPanel = findViewById(R.id.noResultShowPanel);
		mNoResultShowPanel.setVisibility(View.VISIBLE);

		mSearchContentPanel = findViewById(R.id.searchContentPanel);
		mSearchContentPanel.setVisibility(View.INVISIBLE);

		mNotePanel = findViewById(R.id.searchNotePanel);
		mNoteTv = (TextView) findViewById(R.id.searchNoteTv);
		mSwankResultPanel = findViewById(R.id.swankResultPanel);
		mSwankScoreTv = (TextView) findViewById(R.id.swankRankTv);
		mAvgPriceTv = (TextView) findViewById(R.id.priceTv);
		mOverDaysTv = (TextView) findViewById(R.id.overDaysTv);
		
		mViewPager = (ViewPager) findViewById(R.id.pager);
		
		
		Button helpBtn = (Button) findViewById(R.id.helpBtn);
		helpBtn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				startActivity(new Intent(SwankSearchResultActivity.this,
						AboutActivity.class));
			}
		});

		pageNumber = (Button) findViewById(R.id.pageNumBtn);

		Button prevPageBtn = (Button) findViewById(R.id.prevPageBtn);
		prevPageBtn.setTypeface(font);
		prevPageBtn.setOnClickListener(this);

		Button nextPageBtn = (Button) findViewById(R.id.nextPageBtn);
		nextPageBtn.setTypeface(font);
		nextPageBtn.setOnClickListener(this);

		Intent intent = getIntent();
		String strSearchUrl = intent.getStringExtra("searchUrl");
		GlobalFunc.viewLog(Tag, strSearchUrl, true);

		// showTestData();
		swankSearch(strSearchUrl);

	}

	// Show Toast
	private void showToast(String msg) {
		Toast error = Toast.makeText(this, msg, Toast.LENGTH_LONG);
		error.show();
	}

	private void showTestData() {
		mSwankScore = "67.8";
		mAvgPrice = "178.88";
		mSearchNote = "OSNF-AC+ALT";
		searchItems.clear();
		searchItems
				.add(new SwankItem(
						"Silverado PaceSetter QuikTrip Long Tube Headers - 70-2266",
						"http://i.ebayimg.com/00/s/NDAwWDYwMA==/z/QZYAAOSw7I5TtgUH/$_1.JPG?set_id=880000500F",
						"10/10/2014", "256.85"));
		searchItems
				.add(new SwankItem(
						"Silverado PaceSetter QuikTrip Long Tube Headers - 70-2266",
						"http://i.ebayimg.com/00/s/NDAwWDYwMA==/z/QZYAAOSw7I5TtgUH/$_1.JPG?set_id=880000500F",
						"07/10/2014", "256.85"));
		searchItems
				.add(new SwankItem(
						"Hot Wheels Red/Blue Quiktrip Hydr8 Hummer H2",
						"http://i.ebayimg.com/00/s/MTIwMFgxNjAw/z/jGMAAOSwnDZUHZfW/$_1.JPG?set_id=880000500F",
						"22/09/2014", "7.95"));

		// showSearchResults();
	}

	private void swankSearch(String strSearchUrl) {

		if (strSearchUrl == null || strSearchUrl.isEmpty()) {
			showToast("There is no search url.Please try again.");
			finish();
			return;
		}

		// Network state check
		if (!NetStateUtilities.hasConnection(this)) {

			AlertDialog.Builder builder = new AlertDialog.Builder(this);

			// set property
			builder.setTitle("Notice")
					.setMessage("Cannot connect sever. No network connection.")
					.setPositiveButton("Ok",
							new DialogInterface.OnClickListener() {
								public void onClick(DialogInterface dialog,
										int whichButton) {
									dialog.dismiss();
									finish();
								}
							});

			AlertDialog dialog = builder.create();
			dialog.show(); // Show AlertDialog
			return;
		}

		if (mSearchTask != null) {
			mSearchTask.cancel(true);
			mSearchTask = null;
		}

		mSearchTask = new SwankSearchTask(this);
		mSearchTask.execute(strSearchUrl);

	}

	private void showSearchResults() {
		// Show Search Note information
		if (mSearchNote == null || mSearchNote.isEmpty()) {
			mNotePanel.setVisibility(View.GONE);
		} else {
			mNotePanel.setVisibility(View.VISIBLE);
			mNoteTv.setText(mSearchNote);
		}

		if (searchItems.size() == 0) {
			mSwankResultPanel.setVisibility(View.GONE);
			mSearchContentPanel.setVisibility(View.GONE);
			mNoResultShowPanel.setVisibility(View.VISIBLE);
			return;
		} else {
			mSwankResultPanel.setVisibility(View.VISIBLE);
			mSearchContentPanel.setVisibility(View.VISIBLE);
			mNoResultShowPanel.setVisibility(View.GONE);
		}

		// Show Swank Score information
		mSwankScoreTv.setText(mSwankScore);

		// Show Average Price information
		mAvgPriceTv.setText("$" + mAvgPrice);

		mOverDaysTv.setText(mOverDays);
		
		// Show swank items information
		mViewPager.setAdapter(new PagerAdapterClass(getApplicationContext(),
				searchItems));

		mViewPager.setOnPageChangeListener(new OnPageChangeListener() {

			@Override
			public void onPageSelected(int arg0) {
				updatePageNumber();
			}

			@Override
			public void onPageScrollStateChanged(int arg0) {
			}

			@Override
			public void onPageScrolled(int arg0, float arg1, int arg2) {
			}
		});

		updatePageNumber();
	}

	private void updatePageNumber() {
		String pageInfo = String.format("%d/%d",
				mViewPager.getCurrentItem() + 1, searchItems.size());
		pageNumber.setText(pageInfo);
	}

	// AsyncTask<Params,Progress,Result>
	private class SwankSearchTask extends AsyncTask<String, Void, String> {

		private ProgressDialog mDlg;
		private Context mContext;

		public SwankSearchTask(Context context) {
			mContext = context;
		}

		@Override
		protected void onPreExecute() {
			mDlg = new ProgressDialog(mContext);
			mDlg.setProgressStyle(ProgressDialog.STYLE_SPINNER);
			mDlg.setTitle("Please wait.");
			mDlg.setMessage("Swank Search In Progress");
			mDlg.setCanceledOnTouchOutside(false);
			mDlg.setCancelable(false);
			mDlg.show();
			super.onPreExecute();
		}

		@Override
		protected String doInBackground(String... urls) {

			// This method for HttpConnection
			BufferedReader bufferedReader = null;
			try {
				HttpClient client = new DefaultHttpClient();
				client.getParams().setParameter(CoreProtocolPNames.USER_AGENT,
						"android");
				HttpGet request = new HttpGet();
				request.setHeader("Content-Type", "text/plain; charset=utf-8");
				request.setURI(new URI(urls[0]));
				HttpResponse response = client.execute(request);
				bufferedReader = new BufferedReader(new InputStreamReader(
						response.getEntity().getContent()));

				StringBuffer stringBuffer = new StringBuffer("");
				String line = "";

				String NL = System.getProperty("line.separator");
				while ((line = bufferedReader.readLine()) != null) {
					stringBuffer.append(line + NL);
				}
				bufferedReader.close();
				String page = stringBuffer.toString();
				return page;
			} catch (URISyntaxException | IOException e) {
				GlobalFunc.viewLog(Tag,
						"Error occured in reading the plan data.", true);
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

		// http://stoplisting.com/api/?swank&user_id=24&query=@quicktrip
		// [{"status":"200","swank_score":"67.8","avg_price":"173.88","num_results":"3",,"note":"OSNF-AC","results":[{"title":"Silverado PaceSetter QuikTrip Long Tube Headers - 70-2266","image":"http://i.ebayimg.com/00/s/NDAwWDYwMA==/z/QZYAAOSw7I5TtgUH/$_1.JPG?set_id=880000500F","sold_date":"2014-10-10","sold_price":"256.85"},{"title":"Silverado PaceSetter QuikTrip Long Tube Headers - 70-2266","image":"http://i.ebayimg.com/00/s/NDAwWDYwMA==/z/QZYAAOSw7I5TtgUH/$_1.JPG?set_id=880000500F","sold_date":"2014-10-07","sold_price":"256.85"},{"title":"Hot Wheels Red/Blue Quiktrip Hydr8 Hummer H2","image":"http://i.ebayimg.com/00/s/MTIwMFgxNjAw/z/jGMAAOSwnDZUHZfW/$_1.JPG?set_id=880000500F","sold_date":"2014-09-22","sold_price":"7.95"}]}]
		@Override
		protected void onPostExecute(String result) {
			boolean searchSuccess = false;
			String errorMsg = "";
			if (result != null && !result.isEmpty()) {
				try {
					JSONArray searchJson = new JSONArray(result);
					JSONObject searchResult = searchJson.getJSONObject(0);

					if (searchResult != null) {
						// Login status
						String strSearchStatus = searchResult
								.getString("status");
						int loginStatus = Integer.parseInt(strSearchStatus);

						if (loginStatus == 200) {
							searchSuccess = true;

							mSwankScore = searchResult.getString("swank_score");
							mAvgPrice = searchResult.getString("avg_price");
							mOverDays = searchResult.getString("turnover_rate");
							
							String strSearchItems = searchResult.getString("num_results");
							
							if (searchResult.has("note"))
								mSearchNote = searchResult.getString("note");
							
							JSONArray itemsJson = searchResult.getJSONArray("results");
							int itemCount = 0;
							if (itemsJson != null) {
								itemCount = itemsJson.length();
							}
							
							searchItems.clear();
							
							for (int i = 0; i < itemCount; i++) {
								JSONObject curItem = itemsJson.getJSONObject(i);
								if (curItem == null)
									continue;
								
								SwankItem newItem = new SwankItem();
								newItem.setTitle(curItem.getString("title"));
								
								/*String strUrl = curItem.getString("image");
								int nEndPos = strUrl.indexOf("?");
								if (nEndPos != -1) {
									strUrl = strUrl.substring(0, nEndPos);
								}
								newItem.setImageUrl(strUrl);*/
								
								newItem.setImageUrl(curItem.getString("image"));
								newItem.setSoldDate(curItem.getString("sold_date"));
								newItem.setSoldPrice(curItem.getString("sold_price"));
								searchItems.add(newItem);
							}
							
						} else if (loginStatus == 404) {
							searchSuccess = true;
							errorMsg = "There is no search result. Please try again";
						} else {
							// Unknown error
							searchSuccess = false;
							errorMsg = "Search Failure! \n Unknown error.";
						}
					} else {
						// Unknown error
						searchSuccess = false;
						errorMsg = "Search Failure! \n There is no search result. Please try again.";
					}
				} catch (JSONException e) {
					// Json Parsing error
					searchSuccess = false;
					// errorMsg = "Search Failure! \n Please try again.";
					errorMsg = e.getMessage();
				}
			} else {
				// Network error
				searchSuccess = true;
				showTestData();
				// errorMsg =
				// "Search Failure! \nPlease confirm your network connection with server.";
			}

			mDlg.dismiss();
			if (searchSuccess) {
				// success search and show results
				showSearchResults();
			} else {
				// Failed login
				GlobalFunc.viewLog(Tag, "Cannot login to server.", true);
				mDlg.dismiss();
				AlertDialog.Builder builder = new AlertDialog.Builder(mContext);
				// set property
				builder.setTitle("Warning")
						.setMessage(errorMsg)
						.setPositiveButton("Ok",
								new DialogInterface.OnClickListener() {
									public void onClick(DialogInterface dialog,
											int whichButton) {
										dialog.dismiss();
										finish();
									}
								});

				AlertDialog dialog = builder.create();
				dialog.show(); // Show AlertDialog
			}
		}

		@Override
		protected void onCancelled() {
			mDlg.dismiss();
			super.onCancelled();
		}
	}

	@Override
	public void onClick(View v) {
		int id = v.getId();
		int nCurPage = mViewPager.getCurrentItem();
		int nPages = searchItems.size();

		switch (id) {
		case R.id.prevPageBtn:
			if (nCurPage > 0)
				mViewPager.setCurrentItem(nCurPage - 1, true);
			break;
		case R.id.nextPageBtn:
			if (nCurPage < nPages - 1)
				mViewPager.setCurrentItem(nCurPage + 1, true);
			break;
		}
	}

	/**
	 * PagerAdapter
	 */
	private class PagerAdapterClass extends PagerAdapter {

		private LayoutInflater mInflater;
		ArrayList<SwankItem> items;

		public PagerAdapterClass(Context c, ArrayList<SwankItem> swankItems) {
			super();
			mInflater = LayoutInflater.from(c);
			items = swankItems;
		}

		@Override
		public int getCount() {
			return items.size();
		}

		@Override
		public Object instantiateItem(View pager, int position) {
			View v = null;
			v = mInflater.inflate(R.layout.swank_search_item, null);

			ResizableImageView PhotoIv = (ResizableImageView) v
					.findViewById(R.id.itemPhotoIv);
			final ProgressBar imgLoadingProg = (ProgressBar) v
					.findViewById(R.id.imgLoadingProg);
			TextView itemTitleTv = (TextView) v.findViewById(R.id.itemTitleTv);
			TextView lastestSoldDateTv = (TextView) v
					.findViewById(R.id.lastestSoldDateTv);
			TextView lastestSoldPriceTv = (TextView) v
					.findViewById(R.id.lastestSoldPriceTv);

			SwankItem curItem = items.get(position);
			itemTitleTv.setText(curItem.getTitle());
			lastestSoldDateTv.setText(curItem.getSoldDate());
			lastestSoldPriceTv.setText(curItem.getSoldPrice());

			String photoUrl = curItem.getImageUrl();

			//imgLoader.displayImage(photoUrl, PhotoIv, options);
			imgLoader.displayImage(photoUrl, PhotoIv, options,
					new ImageLoadingListener() {

						@Override
						public void onLoadingStarted(String imageUri, View view) {
							imgLoadingProg.setVisibility(View.VISIBLE);
						}

						@Override
						public void onLoadingFailed(String imageUri, View view,
								FailReason failReason) {
							imgLoadingProg.setVisibility(View.INVISIBLE);
						}

						@Override
						public void onLoadingComplete(String imageUri,
								View view, Bitmap loadedImage) {
							imgLoadingProg.setVisibility(View.INVISIBLE);
						}

						@Override
						public void onLoadingCancelled(String imageUri,
								View view) {
							imgLoadingProg.setVisibility(View.INVISIBLE);
						}

					});

			((ViewPager) pager).addView(v, 0);
			return v;
		}

		@Override
		public void destroyItem(View pager, int position, Object view) {
			((ViewPager) pager).removeView((View) view);
		}

		@Override
		public boolean isViewFromObject(View pager, Object obj) {
			return pager == obj;
		}

		@Override
		public void restoreState(Parcelable arg0, ClassLoader arg1) {
		}

		@Override
		public Parcelable saveState() {
			return null;
		}

		@Override
		public void startUpdate(View arg0) {
		}

		@Override
		public void finishUpdate(View arg0) {
		}
	}

}
