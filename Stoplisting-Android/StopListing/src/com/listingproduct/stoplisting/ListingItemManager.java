/*
 * Copyright (c) 2011 Dropbox, Inc.
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

package com.listingproduct.stoplisting;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.MalformedURLException;
import java.net.URI;
import java.net.URISyntaxException;
import java.net.URL;
import java.net.URLConnection;
import java.util.ArrayList;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.CoreProtocolPNames;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.DialogInterface.OnClickListener;
import android.os.AsyncTask;
import android.util.Log;
import android.widget.Toast;

import com.dropbox.client2.DropboxAPI;
import com.dropbox.client2.DropboxAPI.DropboxLink;
import com.dropbox.client2.DropboxAPI.Entry;
import com.dropbox.client2.DropboxAPI.UploadRequest;
import com.dropbox.client2.ProgressListener;
import com.dropbox.client2.exception.DropboxException;
import com.dropbox.client2.exception.DropboxFileSizeException;
import com.dropbox.client2.exception.DropboxIOException;
import com.dropbox.client2.exception.DropboxParseException;
import com.dropbox.client2.exception.DropboxPartialFileException;
import com.dropbox.client2.exception.DropboxServerException;
import com.dropbox.client2.exception.DropboxUnlinkedException;
import com.listingproduct.stoplisting.data.DropboxUrlItem;
import com.listingproduct.stoplisting.global.GlobalDefine;
import com.listingproduct.stoplisting.global.GlobalFunc;

/**
 * Here we show uploading a file in a background thread, trying to show typical
 * exception handling and flow of control for an app that uploads a file from
 * Dropbox.
 */
public class ListingItemManager {

	Context mContext;
	
	int mUserId = -1;
	
	ArrayList<ListingItemTask> taskList = new ArrayList<ListingItemTask>();
	DropboxAPI<?> mApi;
	boolean mBusy = false;
	
	private final String PHOTO_DIR = "/Photos/";

	ListingItemTask curTask;
	
	private String Tag = "stListingItemManager";
	
	// constructor
	ListingItemManager(Context context) {
		mContext = context;
	}
	
	// set DroboxApi
	public void setUserId(int userID) {
		mUserId = userID;
	}
	
	// set DroboxApi
	public void setDropboxApi(DropboxAPI<?> api) {
		mApi = api;
	}
	
	// list Item
	public void listItem(ArrayList<String> fileList) {
		// make new Task and queue it to Task Array.
		ListingItemTask newTask = new ListingItemTask(fileList);
		if (taskList == null)
			taskList = new ArrayList<ListingItemTask>();
		taskList.add(newTask);
		
		// If not busy, execute it.
		if (!mBusy) {
			curTask = taskList.get(0);
			taskList.remove(0);
			mBusy = true;
			curTask.execute();
		}
	}
	
	public void stop(){
		if (curTask != null) {
			taskList.clear();
			curTask.cancel(true);
		}
	}
	
	public class ListingItemTask extends AsyncTask<Void, Long, String> {

		private File mFile;
		private long mFileLen;

		private UploadRequest mRequest;

		private String mErrorMsg;

		private ArrayList<String> mFileList;
		private ArrayList<String> mShareLinkList;

		private String TAG = "UploadPicture";

		public ListingItemTask(ArrayList<String> fileList) {
			mFileList = fileList;
			mShareLinkList = new ArrayList<String>();
		}

		@Override
		protected String doInBackground(Void... params) {
			
			try {

				for (int i = 0; i < mFileList.size(); i++) {

					String curFilePath = mFileList.get(i);
					mFile = new File(curFilePath);

					// check file exist
					if (!mFile.exists())
						continue;

					mFileLen = mFile.length();
					publishProgress((long) 0);

					// By creating a request, we get a handle to the putFile
					// operation,
					// so we can cancel it later if we want to
					FileInputStream fis = new FileInputStream(mFile);
					String path = PHOTO_DIR + mFile.getName();
					mRequest = mApi.putFileOverwriteRequest(path, fis,
							mFile.length(), new ProgressListener() {
								@Override
								public long progressInterval() {
									// Update the progress bar every half-second
									// or so
									return 500;
								}

								@Override
								public void onProgress(long bytes, long total) {
									publishProgress(bytes);
								}
							});

					if (mRequest != null) {
						Entry ent = mRequest.upload();
						String picLinkPath = ent.path;
						DropboxLink shareLink = mApi.share(picLinkPath);
						String shareAddress = getShareURL(shareLink.url)
								.replaceFirst("https://www", "https://dl");
						int lastPos = shareAddress.lastIndexOf("?");
						String realShareAddress = shareAddress;
						if (lastPos != -1)
							realShareAddress = realShareAddress.substring(0, lastPos);
						mShareLinkList.add(realShareAddress);
					}
				}

				if (mShareLinkList.size() == 0)
					return "";
					
				String shareLinkPaths = "";
				for (int i = 0; i < mShareLinkList.size(); i++) {
					String shareLink = mShareLinkList.get(i);
					if (i > 0)
						shareLinkPaths += "-";
					shareLinkPaths += shareLink;
				}
				
				String urlListingItem = String.format(GlobalDefine.SiteURLs.listingURL, shareLinkPaths, mUserId);
				GlobalFunc.viewLog(Tag, urlListingItem, true);
				
				// This method for HttpConnection
				BufferedReader bufferedReader = null;
				try {
					HttpClient client = new DefaultHttpClient();
					client.getParams().setParameter(CoreProtocolPNames.USER_AGENT,
							"android");
					HttpGet request = new HttpGet();
					request.setHeader("Content-Type", "text/plain; charset=utf-8");
					request.setURI(new URI(urlListingItem));
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

			} catch (DropboxUnlinkedException e) {
				// This session wasn't authenticated properly or user unlinked
				mErrorMsg = "This app wasn't authenticated properly.";
			} catch (DropboxFileSizeException e) {
				// File size too big to upload via the API
				mErrorMsg = "This file is too big to upload";
			} catch (DropboxPartialFileException e) {
				// We canceled the operation
				mErrorMsg = "Upload canceled";
			} catch (DropboxServerException e) {
				// Server-side exception. These are examples of what could
				// happen,
				// but we don't do anything special with them here.
				if (e.error == DropboxServerException._401_UNAUTHORIZED) {
					// Unauthorized, so we should unlink them. You may want to
					// automatically log the user out in this case.
				} else if (e.error == DropboxServerException._403_FORBIDDEN) {
					// Not allowed to access this
				} else if (e.error == DropboxServerException._404_NOT_FOUND) {
					// path not found (or if it was the thumbnail, can't be
					// thumbnailed)
				} else if (e.error == DropboxServerException._507_INSUFFICIENT_STORAGE) {
					// user is over quota
				} else {
					// Something else
				}
				// This gets the Dropbox error, translated into the user's
				// language
				mErrorMsg = e.body.userError;
				if (mErrorMsg == null) {
					mErrorMsg = e.body.error;
				}
			} catch (DropboxIOException e) {
				// Happens all the time, probably want to retry automatically.
				mErrorMsg = "Network error.  Try again.";
			} catch (DropboxParseException e) {
				// Probably due to Dropbox server restarting, should retry
				mErrorMsg = "Dropbox error.  Try again.";
			} catch (DropboxException e) {
				// Unknown error
				mErrorMsg = "Unknown error.  Try again.";
			} catch (FileNotFoundException e) {
			}
			return null;
		}

		String getShareURL(String strURL) {
			URLConnection conn = null;
			String redirectedUrl = null;
			try {
				URL inputURL = new URL(strURL);
				conn = inputURL.openConnection();
				conn.connect();

				InputStream is = conn.getInputStream();
				System.out.println("Redirected URL: " + conn.getURL());
				redirectedUrl = conn.getURL().toString();
				is.close();

			} catch (MalformedURLException e) {
				Log.d(TAG, "Please input a valid URL");
			} catch (IOException ioe) {
				Log.d(TAG, "Can not connect to the URL");
			}

			return redirectedUrl;
		}

		@Override
		protected void onProgressUpdate(Long... progress) {
			int percent = (int) (100.0 * (double) progress[0] / mFileLen + 0.5);
		}

		@Override
		protected void onPostExecute(String result) {
			boolean listingSuccess = false;

			if (result == null) {
				
			} else if (result.isEmpty()) {
				// Network error
				listingSuccess = false;
				mErrorMsg = "Listing Failure! \nPlease confirm your network connection with server.";
			} else { //if (result != null && !result.isEmpty()) {
				try {
					result = result.trim();
					//JSONArray loginJson = new JSONArray(result);
					//JSONObject loginResult = loginJson.getJSONObject(0);
					JSONObject listingResult = new JSONObject(result);
						
					if (listingResult != null) {
						// Login status
						String strListingStatus = listingResult.getString("status");
						int listingStatus = Integer.parseInt(strListingStatus);

						if (listingStatus == 200) {
							// successfully listed
							listingSuccess = true;
						} else if (listingStatus == 503) {
							// Invalide user info
							listingSuccess = false;
							mErrorMsg = listingResult.getString("error");
						} else {
							// Unknown error
							listingSuccess = false;
							mErrorMsg = "Listing Failure! \n Please try again.";
						}
					} else {
						// Unknown error
						listingSuccess = false;
						mErrorMsg = "Listing Failure! \n There is no listing result. Please try again.";
					}
				} catch (JSONException e) {
					// Json Parsing error
					listingSuccess = false;
					mErrorMsg = e.getMessage();
				}
			} 

			if (listingSuccess) {
				// success login and goto next page
				GlobalFunc.viewLog(Tag, "Successfully listed new item.", true);
			} else {
				// Failed login
				GlobalFunc.viewLog(Tag, "Cannot list new item.", true);
				GlobalFunc.viewLog(Tag, mErrorMsg, true);
				showToast(mErrorMsg);
			}
			
			// Try to list new item
			if (taskList.size() == 0) {
				mBusy = false;
				curTask = null;
			} else {
				curTask = taskList.get(0);
				taskList.remove(0);
				curTask.execute();
			}		
		}

		private void showToast(String msg) {
			Toast error = Toast.makeText(mContext, msg, Toast.LENGTH_LONG);
			error.show();
		}
	}
}
