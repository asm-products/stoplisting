package com.listingproduct.stoplisting;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;

import com.dropbox.client2.DropboxAPI;
import com.dropbox.client2.DropboxAPI.DropboxLink;
import com.dropbox.client2.DropboxAPI.Entry;
import com.dropbox.client2.DropboxAPI.UploadRequest;
import com.dropbox.client2.ProgressListener;
import com.dropbox.client2.android.AndroidAuthSession;
import com.dropbox.client2.android.AuthActivity;
import com.dropbox.client2.exception.DropboxException;
import com.dropbox.client2.exception.DropboxFileSizeException;
import com.dropbox.client2.exception.DropboxIOException;
import com.dropbox.client2.exception.DropboxParseException;
import com.dropbox.client2.exception.DropboxPartialFileException;
import com.dropbox.client2.exception.DropboxServerException;
import com.dropbox.client2.exception.DropboxUnlinkedException;
import com.dropbox.client2.session.AccessTokenPair;
import com.dropbox.client2.session.AppKeyPair;
import com.listingproduct.stoplisting.data.DropboxUrlItem;

import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.ActivityNotFoundException;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.content.pm.PackageManager;
import android.database.Cursor;
import android.graphics.Typeface;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.provider.MediaStore.Images.Media;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.view.Window;

import android.content.DialogInterface.OnClickListener;

import android.widget.Button;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

public class UploadNewItemActivity extends MyBaseActivity implements View.OnClickListener {

	// Key and Secret assigned by Dropbox.
	final static private String APP_KEY = "daxb0hx4xfzqe6p";
	final static private String APP_SECRET = "8oy8vdg8ioz2q2k";

	// You don't need to change these, leave them alone.
	final static private String ACCOUNT_PREFS_NAME = "prefs";
	final static private String ACCESS_KEY_NAME = "ACCESS_KEY";
	final static private String ACCESS_SECRET_NAME = "ACCESS_SECRET";

	private static final boolean USE_OAUTH1 = false;
	DropboxAPI<AndroidAuthSession> mApi;

	private boolean mLoggedIn;
	
	// Android widgets
	private Button mSubmit;
	private LinearLayout mDisplay;

	private Button pickPhotoBtn;
	private Button uploadPhotoBtn;
	private Button uploadItemBtn;

	private ImageView mImage;
	private final String PHOTO_DIR = "/Photos/";
	
	private Uri mImageUri;
	private boolean bCurImgIsCamera = false;
	
	private String mCameraFileName;
	
	GridView mImageGv;
	DropBoxLinkAdapter mImgAdapter;
	ArrayList<DropboxUrlItem> mUrlList = new ArrayList<DropboxUrlItem>();
	
	final static private int CAMERA_PICTURE = 1;
	final static private int CHOOSE_PICTURE = 2;

	public Typeface font;
	String TAG = "UploadNewItemActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);

		// We create a new AuthSession so that we can use the Dropbox API.
		AndroidAuthSession session = buildSession();
		mApi = new DropboxAPI<AndroidAuthSession>(session);

		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.upload_newitem_screen);

		checkAppKeySetup();

		// load Font
		font = ((Application) getApplication()).getFont();

		// Link Dropbox button
		mSubmit = (Button) findViewById(R.id.authBtn);
		mSubmit.setTypeface(font);
		mSubmit.setOnClickListener(new View.OnClickListener() {
			public void onClick(View v) {
				// This logs you out if you're logged in, or vice versa
				if (mLoggedIn) {
					logOut();
				} else {
					// Start the remote authentication
					if (USE_OAUTH1) {
						mApi.getSession().startAuthentication(
								UploadNewItemActivity.this);
					} else {
						mApi.getSession().startOAuth2Authentication(
								UploadNewItemActivity.this);
					}
				}
			}
		});

		// Logged Layout
		mDisplay = (LinearLayout) findViewById(R.id.logged_in_display);

		// Pick Photo Button
		pickPhotoBtn = (Button) findViewById(R.id.pickPhotoBtn);
		pickPhotoBtn.setTypeface(font);
		pickPhotoBtn.setOnClickListener(this);

		// Upload Button
		uploadPhotoBtn = (Button) findViewById(R.id.uploadPhotoBtn);
		uploadPhotoBtn.setTypeface(font);
		uploadPhotoBtn.setOnClickListener(this);

		// Add Item Button
		uploadItemBtn = (Button) findViewById(R.id.uploadItemBtn);
		uploadItemBtn.setTypeface(font);
		uploadItemBtn.setOnClickListener(this);

		// Current Item Photo
		mImage = (ImageView) findViewById(R.id.itemPhotoIv);

		// set Adapter
		mImageGv = (GridView) findViewById(R.id.imagesGv);
		mImgAdapter = new DropBoxLinkAdapter(this, R.layout.upload_image_griditem, mUrlList);
		mImageGv.setAdapter(mImgAdapter);
		
		// Display the proper UI state if logged in or not
		setLoggedIn(mApi.getSession().isLinked());
		
		Log.e(TAG, "OnCreate()********************************");
	}
	
	@Override
    protected void onSaveInstanceState(Bundle outState) {
        outState.putString("mCameraFileName", mCameraFileName);
        super.onSaveInstanceState(outState);
    }
	
	@Override
	protected void onRestoreInstanceState(Bundle savedInstanceState) {
	    super.onRestoreInstanceState(savedInstanceState);
	    if (savedInstanceState.containsKey("mCameraFileName")) {
	    	mCameraFileName = savedInstanceState.getString("mCameraFileName");
	    }
	}
	
	@Override
	protected void onResume() {
		super.onResume();
		AndroidAuthSession session = mApi.getSession();

		// The next part must be inserted in the onResume() method of the
		// activity from which session.startAuthentication() was called, so
		// that Dropbox authentication completes properly.
		if (session.authenticationSuccessful()) {
			try {
				// Mandatory call to complete the auth
				session.finishAuthentication();

				// Store it locally in our app for later use
				storeAuth(session);
				setLoggedIn(true);
			} catch (IllegalStateException e) {
				showToast("Couldn't authenticate with Dropbox:"
						+ e.getLocalizedMessage());
				Log.i(TAG, "Error authenticating", e);
			}
		}
	}

	@Override
	public void onClick(View v) {
		int viewId = v.getId();
		switch (viewId) {
		case R.id.pickPhotoBtn:
			ShowLoadPhotoDlg();
			break;
		case R.id.uploadPhotoBtn:
			UploadCurrentImage();
			break;
		case R.id.uploadItemBtn:
			UploadCurrentItem();
			break;
		}
	}

	private void ShowLoadPhotoDlg() {

		final Dialog myDialog = new Dialog(UploadNewItemActivity.this,
				R.style.CustomTheme);

		myDialog.setContentView(R.layout.load_photo_dialog);
		myDialog.setTitle("Choose Library");
		TextView takePhotoBtn = (TextView) myDialog
				.findViewById(R.id.takePhotoBtn);

		takePhotoBtn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				
				Intent cameraIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
                
                // This is not the right way to do this, but for some reason, having
                // it store it in
                // MediaStore.Images.Media.EXTERNAL_CONTENT_URI isn't working right.

                // make App directory
                File appDir = new File(Environment.getExternalStorageDirectory() + File.separator + getString(R.string.app_name));
				if (!appDir.exists())
					appDir.mkdir();
                
                Date date = new Date();
                DateFormat df = new SimpleDateFormat("yyyy-MM-dd-kk-mm-ss");
                String newPicFile = df.format(date) + ".jpg";
                
                String outPath = new File(appDir, newPicFile).getPath();
                File outFile = new File(outPath);

                //mCameraFileName = outFile.toString();
                mCameraFileName = outFile.getAbsolutePath();
                Uri outuri = Uri.fromFile(outFile);
                cameraIntent.putExtra(MediaStore.EXTRA_OUTPUT, outuri);
                Log.i(TAG, "Importing New Picture: " + mCameraFileName);
                try {
                    startActivityForResult(cameraIntent, CAMERA_PICTURE);
                } catch (ActivityNotFoundException e) {
                    showToast("There doesn't seem to be a camera.");
                }
                myDialog.dismiss();
			}
		});

		TextView chooseFromBtn = (TextView) myDialog
				.findViewById(R.id.chooseFromBtn);
		chooseFromBtn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {				
				Intent galleryIntent = new Intent(Intent.ACTION_PICK, Media.EXTERNAL_CONTENT_URI);
			    startActivityForResult(galleryIntent, CHOOSE_PICTURE);
			    myDialog.dismiss();
			}
		});

		TextView tvCancelButton = (TextView) myDialog
				.findViewById(R.id.tvCancel);
		tvCancelButton.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				myDialog.dismiss();
			}
		});

		myDialog.getWindow().getAttributes().windowAnimations = R.style.DialogAnimation;
		Window window = myDialog.getWindow();
		
		// 정확한 위치조절은 다이얼로그 대면부 xml파일에서 
		// 어미LinearLayout의 android:layout_gravity파라메터값을 조절할것.
		window.setGravity(Gravity.CENTER);
		myDialog.show();
	}
	
	private void UploadCurrentImage() {
		if (mImageUri == null)
			return;
	
		// Open file
		File file = null;
		// 
		if (bCurImgIsCamera) {
			file = new File(mImageUri.getPath());
		} else {
			file = new File(getPath(mImageUri));
		}
		
		if (!file.exists() || !file.isFile()) {
			return;
		}
		
		if (mImageUri != null) {
            UploadPicture upload = new UploadPicture(this, mApi, PHOTO_DIR, file, mImgAdapter);
            upload.execute();
        }
	}
	
	private void UploadCurrentItem() {
		if (mUrlList.size() == 0) {
			Toast.makeText(this, "There is no item photo. Please upload item photos on your Dropbox.", Toast.LENGTH_LONG).show();
			return;
		}
		
		// Upload Processing		
		mImage.setImageURI(null);
		mUrlList.clear();
		mImgAdapter.notifyDataSetChanged();
		Toast.makeText(this, "Successfully uploaded!", Toast.LENGTH_LONG).show();
	}
	
	public String getPath(Uri uri) {
		String[] projection = { MediaStore.Images.Media.DATA };
		Cursor cursor = managedQuery(uri, projection, null, null, null);
		startManagingCursor(cursor);
		int columnIndex = cursor
				.getColumnIndexOrThrow(MediaStore.Images.Media.DATA);
		cursor.moveToFirst();
		return cursor.getString(columnIndex);
	}
	
	@Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
		
		if (resultCode == Activity.RESULT_OK) {
        	mImageUri = null;
        	// return from file upload
            if (requestCode == CAMERA_PICTURE) {
                // Camera Image
            	if (data != null) {
                	mImageUri = data.getData();
                }
                
                if (mImageUri == null && mCameraFileName != null) {
                	mImageUri = Uri.fromFile(new File(mCameraFileName));
                }
                bCurImgIsCamera = true;
            } else if (requestCode == CHOOSE_PICTURE) {
            	// Gallery Image
            	if (data != null) {
            		mImageUri = data.getData();
                }            	
            	bCurImgIsCamera = false;
            }
            
            if(mImageUri != null) {
            	mImage.setImageURI(mImageUri);
            }
            
            System.gc();
        }
    }
	
	private void logOut() {
		// Remove credentials from the session
		mApi.getSession().unlink();

		// Clear our stored keys
		clearKeys();
		// Change UI state to display logged out version
		setLoggedIn(false);
	}

	/**
	 * Convenience function to change UI state based on being logged in
	 */
	private void setLoggedIn(boolean loggedIn) {
		mLoggedIn = loggedIn;
		if (loggedIn) {
			mSubmit.setText(getString(R.string.title_unlink_dropbox));
			mDisplay.setVisibility(View.VISIBLE);
		} else {
			mSubmit.setText(getString(R.string.title_link_dropbox));
			mDisplay.setVisibility(View.GONE);
			mImage.setImageDrawable(null);
		}
	}

	private void checkAppKeySetup() {
		// Check to make sure that we have a valid app key
		if (APP_KEY.startsWith("CHANGE") || APP_SECRET.startsWith("CHANGE")) {
			showToast("You must apply for an app key and secret from developers.dropbox.com, and add them to the app before trying it.");
			finish();
			return;
		}

		// Check if the app has set up its manifest properly.
		Intent testIntent = new Intent(Intent.ACTION_VIEW);
		String scheme = "db-" + APP_KEY;
		String uri = scheme + "://" + AuthActivity.AUTH_VERSION + "/test";
		testIntent.setData(Uri.parse(uri));
		PackageManager pm = getPackageManager();
		if (0 == pm.queryIntentActivities(testIntent, 0).size()) {
			showToast("URL scheme in your app's "
					+ "manifest is not set up correctly. You should have a "
					+ "com.dropbox.client2.android.AuthActivity with the "
					+ "scheme: " + scheme);
			finish();
		}
	}

	private void showToast(String msg) {
		Toast error = Toast.makeText(this, msg, Toast.LENGTH_LONG);
		error.show();
	}

	/**
	 * Shows keeping the access keys returned from Trusted Authenticator in a
	 * local store, rather than storing user name & password, and
	 * re-authenticating each time (which is not to be done, ever).
	 */
	private void loadAuth(AndroidAuthSession session) {
		SharedPreferences prefs = getSharedPreferences(ACCOUNT_PREFS_NAME, 0);
		String key = prefs.getString(ACCESS_KEY_NAME, null);
		String secret = prefs.getString(ACCESS_SECRET_NAME, null);
		if (key == null || secret == null || key.length() == 0
				|| secret.length() == 0)
			return;

		if (key.equals("oauth2:")) {
			// If the key is set to "oauth2:", then we can assume the token is
			// for OAuth 2.
			session.setOAuth2AccessToken(secret);
		} else {
			// Still support using old OAuth 1 tokens.
			session.setAccessTokenPair(new AccessTokenPair(key, secret));
		}
	}

	/**
	 * Shows keeping the access keys returned from Trusted Authenticator in a
	 * local store, rather than storing user name & password, and
	 * re-authenticating each time (which is not to be done, ever).
	 */
	private void storeAuth(AndroidAuthSession session) {
		// Store the OAuth 2 access token, if there is one.
		String oauth2AccessToken = session.getOAuth2AccessToken();
		if (oauth2AccessToken != null) {
			SharedPreferences prefs = getSharedPreferences(ACCOUNT_PREFS_NAME,
					0);
			Editor edit = prefs.edit();
			edit.putString(ACCESS_KEY_NAME, "oauth2:");
			edit.putString(ACCESS_SECRET_NAME, oauth2AccessToken);
			edit.commit();
			return;
		}
		// Store the OAuth 1 access token, if there is one. This is only
		// necessary if
		// you're still using OAuth 1.
		AccessTokenPair oauth1AccessToken = session.getAccessTokenPair();
		if (oauth1AccessToken != null) {
			SharedPreferences prefs = getSharedPreferences(ACCOUNT_PREFS_NAME,
					0);
			Editor edit = prefs.edit();
			edit.putString(ACCESS_KEY_NAME, oauth1AccessToken.key);
			edit.putString(ACCESS_SECRET_NAME, oauth1AccessToken.secret);
			edit.commit();
			return;
		}
	}

	private void clearKeys() {
		SharedPreferences prefs = getSharedPreferences(ACCOUNT_PREFS_NAME, 0);
		Editor edit = prefs.edit();
		edit.clear();
		edit.commit();
	}

	private AndroidAuthSession buildSession() {
		AppKeyPair appKeyPair = new AppKeyPair(APP_KEY, APP_SECRET);

		AndroidAuthSession session = new AndroidAuthSession(appKeyPair);
		loadAuth(session);
		return session;
	}
	
	public class UploadPicture extends AsyncTask<Void, Long, String> {

		private DropboxAPI<?> mApi;
		private String mPath;
		private File mFile;
		private DropBoxLinkAdapter mAdapter;
		
		private long mFileLen;
		private UploadRequest mRequest;
		private Context mContext;
		private final ProgressDialog mDialog;

		private String mErrorMsg;

		private String TAG = "UploadPicture";

		public UploadPicture(Context context, DropboxAPI<?> api,
				String dropboxPath, File file, DropBoxLinkAdapter adapter) {
			// We set the context this way so we don't accidentally leak activities
			mContext = context.getApplicationContext();

			mFileLen = file.length();
			mApi = api;
			mPath = dropboxPath;
			mFile = file;
			mAdapter = adapter;
			
			mDialog = new ProgressDialog(context);
			mDialog.setMax(100);
			mDialog.setMessage("Uploading " + file.getName());
			mDialog.setProgressStyle(ProgressDialog.STYLE_HORIZONTAL);
			mDialog.setCanceledOnTouchOutside(false);
			mDialog.setCancelable(false);
			mDialog.setProgress(0);
			
			//import android.content.DialogInterface.OnClickListener;
			mDialog.setButton(ProgressDialog.BUTTON_POSITIVE, "Cancel",
				new OnClickListener() {
					@Override
					public void onClick(DialogInterface dialog, int which) {
						// This will cancel the putFile operation
						mRequest.abort();
					}
				});
			mDialog.show();
		}

		@Override
		protected String doInBackground(Void... params) {
			//return "aaa";
			try {
				
				// By creating a request, we get a handle to the putFile operation,
				// so we can cancel it later if we want to
				FileInputStream fis = new FileInputStream(mFile);
				String path = mPath + mFile.getName();
				mRequest = mApi.putFileOverwriteRequest(path, fis, mFile.length(),
						new ProgressListener() {
							@Override
							public long progressInterval() {
								// Update the progress bar every half-second or so
								return 300;
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
					String shareAddress = getShareURL(shareLink.url).replaceFirst(
							"https://www", "https://dl");
					return shareAddress;
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
				// Server-side exception. These are examples of what could happen,
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
				// This gets the Dropbox error, translated into the user's language
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
			mDialog.setProgress(percent);
		}

		@Override
		protected void onPostExecute(String result) {
			mDialog.dismiss();
			if (result != null && !result.isEmpty()) {
				DropboxUrlItem newItem = new DropboxUrlItem(mFile, result);
				mAdapter.addNewItem(newItem);
				showToast("Image successfully uploaded");
				mImageUri = null;
				mImage.setImageURI(mImageUri);
			} else {
				showToast(mErrorMsg);
			}
		}

		private void showToast(String msg) {
			Toast error = Toast.makeText(mContext, msg, Toast.LENGTH_LONG);
			error.show();
		}
	}
}
