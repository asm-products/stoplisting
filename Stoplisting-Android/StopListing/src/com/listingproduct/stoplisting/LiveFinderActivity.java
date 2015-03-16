package com.listingproduct.stoplisting;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.MalformedURLException;
import java.net.URI;
import java.net.URISyntaxException;
import java.net.URL;
import java.net.URLConnection;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.CoreProtocolPNames;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.dropbox.client2.DropboxAPI;
import com.dropbox.client2.ProgressListener;
import com.dropbox.client2.DropboxAPI.DropboxLink;
import com.dropbox.client2.DropboxAPI.Entry;
import com.dropbox.client2.DropboxAPI.UploadRequest;
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
import com.listingproduct.stoplisting.global.GlobalDefine;
import com.listingproduct.stoplisting.global.GlobalFunc;
import com.listingproduct.stoplisting.task.LiveFinderUpdateCheckingTask;
import com.listingproduct.stoplisting.utilities.NetStateUtilities;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.DialogInterface.OnClickListener;
import android.content.SharedPreferences.Editor;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Typeface;
import android.hardware.Camera;
import android.hardware.Camera.ShutterCallback;
import android.media.AudioManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Environment;
import android.util.Log;
import android.view.Display;
import android.view.Surface;
import android.view.SurfaceHolder;
import android.view.SurfaceView;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.Toast;

import com.listingproduct.stoplisting.utilities.ImageUtils;

public class LiveFinderActivity extends MyBaseActivity {

	// About Dropbox Link
	// Key and Secret assigned by Dropbox.
	final static private String APP_KEY = GlobalDefine.DropBoxInfo.APP_KEY;
	final static private String APP_SECRET = GlobalDefine.DropBoxInfo.APP_SECRET;

	// You don't need to change these, leave them alone.
	final static private String ACCOUNT_PREFS_NAME = GlobalDefine.DropBoxInfo.ACCOUNT_PREFS_NAME;
	final static private String ACCESS_KEY_NAME = GlobalDefine.DropBoxInfo.ACCESS_KEY_NAME;
	final static private String ACCESS_SECRET_NAME = GlobalDefine.DropBoxInfo.ACCESS_SECRET_NAME;

	private static final boolean USE_OAUTH1 = false;
	DropboxAPI<AndroidAuthSession> mApi;

	private int mUserId;
	private boolean mLoggedIn;

	// Android widgets
	private Button mSubmit;
	private View mDisplay;

	// Native camera.
	private Camera mCamera;

	// View to display the camera output.
	private CameraPreview mPreview;

	// Reference to the containing view.
	private View mCameraView;

	public Typeface font;
	String Tag = "stLiveFinderActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.livefinder_screen);

		mUserId = ((Application) getApplication()).getUserId();

		// We create a new AuthSession so that we can use the Dropbox API.
		AndroidAuthSession session = buildSession();
		mApi = new DropboxAPI<AndroidAuthSession>(session);
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
								LiveFinderActivity.this);
					} else {
						mApi.getSession().startOAuth2Authentication(
								LiveFinderActivity.this);
					}
				}
			}
		});

		// Logged Layout
		mDisplay = findViewById(R.id.logged_in_display);

		// Close button
		Button backBtn = (Button) findViewById(R.id.backBtn);
		backBtn.setTypeface(font);
		backBtn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				finish();
			}
		});

		// Camera button
		Button cameraBtn = (Button) findViewById(R.id.cameraBtn);
		cameraBtn.setTypeface(font);
		cameraBtn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				// get an image from the camera
				if (mCamera != null) 
					mCamera.takePicture(mShutter, null, mPicture);
			}
		});
		
		// uploadCurItemPhotos();
		// Display the proper UI state if logged in or not
		setLoggedIn(mApi.getSession().isLinked());
		
	}

	@Override
	public void onResume() {
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
				Log.i(Tag, "Error authenticating", e);
			}
		}
	}

	/**
	 * Recommended "safe" way to open the camera. 
	 * 
	 * @param view
	 * @return
	 */
	private boolean safeCameraOpenInView(View view) {
		boolean qOpened = false;
		releaseCameraAndPreview();
		mCamera = getCameraInstance();
		mCameraView = view;

		qOpened = (mCamera != null);

		if (qOpened == true) {
			mPreview = new CameraPreview(this.getBaseContext(), mCamera, view);
			FrameLayout preview = (FrameLayout) view
					.findViewById(R.id.camera_preview);
			preview.removeAllViews();
			preview.addView(mPreview);
			mPreview.startCameraPreview();
		}
		return qOpened;
	}

	/**
	 * Safe method for getting a camera instance.
	 * 
	 * @return
	 */
	public static Camera getCameraInstance() {
		Camera c = null;
		try {
			c = Camera.open(); // attempt to get a Camera instance
		} catch (Exception e) {
			e.printStackTrace();
		}
		return c; // returns null if camera is unavailable
	}

	@Override
	public void onPause() {
		super.onPause();
	}

	@Override
	public void onDestroy() {
		super.onDestroy();	
		releaseCameraAndPreview();
	}

	/**
	 * Clear any existing preview / camera.
	 */
	private void releaseCameraAndPreview() {

		if (mCamera != null) {
			mCamera.stopPreview();
			mCamera.release();
			mCamera = null;
		}
		if (mPreview != null) {
			mPreview.destroyDrawingCache();
			mPreview.mCamera = null;
			mPreview = null;
		}
	}

	/**
	 * Surface on which the camera projects it's capture results. This is
	 * derived both from Google's docs and the excellent StackOverflow answer
	 * provided below.
	 * 
	 * Reference / Credit:
	 * http://stackoverflow.com/questions/7942378/android-camera
	 * -will-not-work-startpreview-fails
	 */
	class CameraPreview extends SurfaceView implements SurfaceHolder.Callback {

		// SurfaceHolder
		private SurfaceHolder mHolder;

		// Our Camera.
		private Camera mCamera;

		// Parent Context.
		private Context mContext;

		// Camera Sizing (For rotation, orientation changes)
		private Camera.Size mPreviewSize;

		// List of supported preview sizes
		private List<Camera.Size> mSupportedPreviewSizes;

		// Flash modes supported by this camera
		private List<String> mSupportedFlashModes;

		// View holding this camera.
		private View mCameraView;

		public CameraPreview(Context context, Camera camera, View cameraView) {
			super(context);

			// Capture the context
			mCameraView = cameraView;
			mContext = context;
			setCamera(camera);

			// Install a SurfaceHolder.Callback so we get notified when the
			// underlying surface is created and destroyed.
			mHolder = getHolder();
			mHolder.addCallback(this);
			mHolder.setKeepScreenOn(true);
			// deprecated setting, but required on Android versions prior to 3.0
			mHolder.setType(SurfaceHolder.SURFACE_TYPE_PUSH_BUFFERS);
		}

		/**
		 * Begin the preview of the camera input.
		 */
		public void startCameraPreview() {
			try {
				mCamera.setPreviewDisplay(mHolder);
				mCamera.startPreview();
			} catch (Exception e) {
				e.printStackTrace();
			}
		}

		/**
		 * Extract supported preview and flash modes from the camera.
		 * 
		 * @param camera
		 */
		private void setCamera(Camera camera) {
			// Source:
			// http://stackoverflow.com/questions/7942378/android-camera-will-not-work-startpreview-fails
			mCamera = camera;
			mSupportedPreviewSizes = mCamera.getParameters()
					.getSupportedPreviewSizes();
			mSupportedFlashModes = mCamera.getParameters()
					.getSupportedFlashModes();

			// Set the camera to Auto Flash mode.
			if (mSupportedFlashModes != null
					&& mSupportedFlashModes
							.contains(Camera.Parameters.FLASH_MODE_AUTO)) {
				Camera.Parameters parameters = mCamera.getParameters();
				parameters.setFlashMode(Camera.Parameters.FLASH_MODE_AUTO);

				mCamera.setParameters(parameters);
			}

			requestLayout();
		}

		/**
		 * The Surface has been created, now tell the camera where to draw the
		 * preview.
		 * 
		 * @param holder
		 */
		public void surfaceCreated(SurfaceHolder holder) {
			try {
				if (mCamera != null)
					mCamera.setPreviewDisplay(holder);
			} catch (IOException e) {
				e.printStackTrace();
			}
		}

		/**
		 * Dispose of the camera preview.
		 * 
		 * @param holder
		 */
		public void surfaceDestroyed(SurfaceHolder holder) {
			if (mCamera != null) {
				mCamera.stopPreview();
			}
		}

		/**
		 * React to surface changed events
		 * 
		 * @param holder
		 * @param format
		 * @param w
		 * @param h
		 */
		public void surfaceChanged(SurfaceHolder holder, int format, int w,
				int h) {
			// If your preview can change or rotate, take care of those events
			// here.
			// Make sure to stop the preview before resizing or reformatting it.

			if (mHolder.getSurface() == null) {
				// preview surface does not exist
				return;
			}

			// stop preview before making changes
			try {
				Camera.Parameters parameters = mCamera.getParameters();

				// Set the auto-focus mode to "continuous"
				parameters
						.setFocusMode(Camera.Parameters.FOCUS_MODE_CONTINUOUS_PICTURE);

				// Preview size must exist.
				if (mPreviewSize != null) {
					Camera.Size previewSize = mPreviewSize;

					// set Preview Size
					parameters.setPreviewSize(previewSize.width,
							previewSize.height);

					// set Image Size
					// parameters.setPictureSize(previewSize.width,
					// previewSize.height);
				}

				parameters.setRotation(90);

				int degress = getRotationDegrees();
				mCamera.setDisplayOrientation(degress);

				mCamera.setParameters(parameters);
				mCamera.startPreview();
			} catch (Exception e) {
				e.printStackTrace();
			}
		}

		int getRotationDegrees() {
			android.hardware.Camera.CameraInfo info = new android.hardware.Camera.CameraInfo();
			android.hardware.Camera.getCameraInfo(0, info);
			Display display = ((WindowManager) getSystemService(Context.WINDOW_SERVICE))
					.getDefaultDisplay();

			int rotation = display.getRotation();
			int degrees = 0, result = 0;

			switch (rotation) {
			case Surface.ROTATION_0:
				degrees = 0;
				break;
			case Surface.ROTATION_90:
				degrees = 90;
				break;
			case Surface.ROTATION_180:
				degrees = 180;
				break;
			case Surface.ROTATION_270:
				degrees = 270;
				break;
			}

			result = (info.orientation - degrees + 360) % 360;
			Log.i("Orientation", String.valueOf(info.orientation) + "_"
					+ String.valueOf(degrees));
			return result;
		}

		/**
		 * Calculate the measurements of the layout
		 * 
		 * @param widthMeasureSpec
		 * @param heightMeasureSpec
		 */
		@Override
		protected void onMeasure(int widthMeasureSpec, int heightMeasureSpec) {
			// Source:
			// http://stackoverflow.com/questions/7942378/android-camera-will-not-work-startpreview-fails
			final int width = resolveSize(getSuggestedMinimumWidth(),
					widthMeasureSpec);
			final int height = resolveSize(getSuggestedMinimumHeight(),
					heightMeasureSpec);
			setMeasuredDimension(width, height);

			if (mSupportedPreviewSizes != null) {
				mPreviewSize = getOptimalPreviewSize(mSupportedPreviewSizes,
						width, height);
			} else {
				GlobalFunc.viewLog(Tag, "There is no Supported PreviewSizes",
						true);
			}
		}

		/**
		 * Update the layout based on rotation and orientation changes.
		 * 
		 * @param changed
		 * @param left
		 * @param top
		 * @param right
		 * @param bottom
		 */
		@Override
		protected void onLayout(boolean changed, int left, int top, int right,
				int bottom) {
			// Source:
			// http://stackoverflow.com/questions/7942378/android-camera-will-not-work-startpreview-fails
			if (changed) {
				final int width = right - left;
				final int height = bottom - top;

				int previewWidth = width;
				int previewHeight = height;

				if (mPreviewSize != null) {
					Display display = ((WindowManager) mContext
							.getSystemService(Context.WINDOW_SERVICE))
							.getDefaultDisplay();

					switch (display.getRotation()) {
					case Surface.ROTATION_0:
						previewWidth = mPreviewSize.height;
						previewHeight = mPreviewSize.width;
						mCamera.setDisplayOrientation(90);
						break;
					case Surface.ROTATION_90:
						previewWidth = mPreviewSize.width;
						previewHeight = mPreviewSize.height;
						break;
					case Surface.ROTATION_180:
						previewWidth = mPreviewSize.height;
						previewHeight = mPreviewSize.width;
						break;
					case Surface.ROTATION_270:
						previewWidth = mPreviewSize.width;
						previewHeight = mPreviewSize.height;
						mCamera.setDisplayOrientation(180);
						break;
					}
				}

				final int scaledChildHeight = previewHeight * width
						/ previewWidth;

				GlobalFunc.viewLog(Tag, "OnLayout", true);
				GlobalFunc.viewLog(Tag, "leftRight : "
						+ (height - scaledChildHeight) + "-", true);
				GlobalFunc.viewLog(Tag, "widthHeight  : " + width + "-"
						+ height, true);
				// mCameraView.layout(0, height - scaledChildHeight, width,
				// height);
			}
		}

		/**
		 * 
		 * @param sizes
		 * @param width
		 * @param height
		 * @return
		 */
		private Camera.Size getOptimalPreviewSize(List<Camera.Size> sizes,
				int width, int height) {
			// Source:
			// http://stackoverflow.com/questions/7942378/android-camera-will-not-work-startpreview-fails
			Camera.Size optimalSize = null;

			final double ASPECT_TOLERANCE = 0.1;
			double targetRatio = (double) height / width;

			// Try to find a size match which suits the whole screen minus the
			// menu on the left.
			for (Camera.Size size : sizes) {

				GlobalFunc.viewLog(Tag, "optimized size:" + size.width + "-"
						+ size.height, true);

				if (size.height != width)
					continue;
				double ratio = (double) size.width / size.height;
				if (ratio <= targetRatio + ASPECT_TOLERANCE
						&& ratio >= targetRatio - ASPECT_TOLERANCE) {
					optimalSize = size;
				}
			}

			// If we cannot find the one that matches the aspect ratio, ignore
			// the requirement.
			if (optimalSize == null) {
				// TODO : Backup in case we don't get a size.
			} else {
				GlobalFunc.viewLog(Tag, "optimized size:" + optimalSize.width
						+ "-" + optimalSize.height, true);
			}
			return optimalSize;
		}
	}

	private ShutterCallback mShutter = new ShutterCallback() {
		@Override
		public void onShutter() {
			AudioManager mgr = (AudioManager) getSystemService(Context.AUDIO_SERVICE);
			mgr.playSoundEffect(AudioManager.FLAG_PLAY_SOUND);
		}
	};

	/**
	 * Picture Callback for handling a picture capture and saving it out to a
	 * file.
	 */
	private Camera.PictureCallback mPicture = new Camera.PictureCallback() {

		@Override
		public void onPictureTaken(byte[] data, Camera camera) {
			final File pictureFile = getOutputMediaFile();
			if (pictureFile == null) {
				Toast.makeText(LiveFinderActivity.this,
						"Image retrieval failed.", Toast.LENGTH_SHORT).show();
				return;
			}

			try {
				FileOutputStream fos = new FileOutputStream(pictureFile);
				fos.write(data);
				fos.close();

				GlobalFunc.viewLog(Tag, pictureFile.getAbsolutePath(), true);

				// Restart the camera preview.
				safeCameraOpenInView(mCameraView);

				AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(
						LiveFinderActivity.this);
				alertDialogBuilder.setTitle("Ready To Send To Livefinder");
				alertDialogBuilder
						.setMessage(
								"Your photo has been saved. Would you like to send it to Livefinder?")
						.setCancelable(false)
						.setPositiveButton("Send Photo",
								new DialogInterface.OnClickListener() {
									public void onClick(DialogInterface dialog,
											int id) {
										dialog.cancel();
										requestLiveFinder(pictureFile);
									}
								})
						.setNegativeButton("No Thanks",
								new DialogInterface.OnClickListener() {
									public void onClick(DialogInterface dialog,
											int id) {
										dialog.cancel();
										pictureFile.delete();
									}
								});
				AlertDialog alertDialog = alertDialogBuilder.create();
				alertDialog.show();

			} catch (FileNotFoundException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
	};

	/**
	 * Used to return the camera File output.
	 * 
	 * @return
	 */
	private File getOutputMediaFile() {

		File mediaStorageDir = new File(
				Environment
						.getExternalStoragePublicDirectory(Environment.DIRECTORY_PICTURES),
				"StopListing");

		if (!mediaStorageDir.exists()) {
			if (!mediaStorageDir.mkdirs()) {
				Log.d("Camera Guide", "Required media storage does not exist");
				return null;
			}
		}

		// Create a media file name
		String timeStamp = new SimpleDateFormat("yyyyMMdd_HHmmss")
				.format(new Date());
		File mediaFile;
		mediaFile = new File(mediaStorageDir.getPath() + File.separator
				+ "IMG_" + timeStamp + ".jpg");

		// Show Success dialog.
		// DialogHelper.showDialog("Success!", "Your picture has been saved!",
		// this);

		return mediaFile;
	}

	/**
	 * Scale the photo down and fit it to our image views.
	 * 
	 * "Drastically increases performance" to set images using this technique.
	 * Read more:http://developer.android.com/training/camera/photobasics.html
	 */
	private void setFullImageFromFilePath(String imagePath, ImageView imageView) {
		// Get the dimensions of the View
		int targetW = imageView.getWidth();
		int targetH = imageView.getHeight();

		// Get the dimensions of the bitmap
		BitmapFactory.Options bmOptions = new BitmapFactory.Options();
		bmOptions.inJustDecodeBounds = true;
		BitmapFactory.decodeFile(imagePath, bmOptions);
		int photoW = bmOptions.outWidth;
		int photoH = bmOptions.outHeight;

		// Determine how much to scale down the image
		int scaleFactor = Math.min(photoW / targetW, photoH / targetH);

		// Decode the image file into a Bitmap sized to fill the View
		bmOptions.inJustDecodeBounds = false;
		bmOptions.inSampleSize = scaleFactor;
		bmOptions.inPurgeable = true;

		Bitmap bitmap = BitmapFactory.decodeFile(imagePath, bmOptions);
		Bitmap rotateBitmap = ImageUtils.rotateBitmap(bitmap, 90);
		imageView.setImageBitmap(rotateBitmap);
	}

	private void showToast(String msg) {
		Toast error = Toast.makeText(this, msg, Toast.LENGTH_LONG);
		error.show();
	}

	private final String PHOTO_DIR = "/Photos/";

	private void requestLiveFinder(File file) {

		// Network state check
		if (!NetStateUtilities.hasConnection(this)) {

			AlertDialog.Builder builder = new AlertDialog.Builder(this);

			// set property
			builder.setTitle("Notice")
					.setMessage(
							"Cannot list current item. No network connection.")
					.setPositiveButton("Ok",
							new DialogInterface.OnClickListener() {
								public void onClick(DialogInterface dialog,
										int whichButton) {
									dialog.dismiss();
								}
							});

			AlertDialog dialog = builder.create();
			dialog.show(); // Show AlertDialog
			return;
		}
		if (!file.exists())
			return;

		LiveFinderRequestTask upload = new LiveFinderRequestTask(this, mApi, PHOTO_DIR, file);
		upload.execute();
	}

	public class LiveFinderRequestTask extends AsyncTask<Void, Long, String> {

		private DropboxAPI<?> mApi;

		private String mPath;
		private File mFile;
		private long mFileLen;

		private UploadRequest mRequest;
		private Context mContext;
		private final ProgressDialog mDialog;
		private String mDialogMsg;

		private String mErrorMsg;

		private String TAG = "UploadPicture";

		public LiveFinderRequestTask(Context context, DropboxAPI<?> api,
				String dropboxPath, File file) {
			// We set the context this way so we don't accidentally leak
			// activities
			mContext = context.getApplicationContext();

			mApi = api;
			mPath = dropboxPath;
			mFile = file;
			mFileLen = mFile.length();
			mDialog = new ProgressDialog(context);
			mDialog.setMax(100);
			mDialog.setTitle("Please wait...");
			mDialogMsg = "Uploading file.";			
			mDialog.setMessage("Uploading file.");
			mDialog.setProgressStyle(ProgressDialog.STYLE_HORIZONTAL);
			mDialog.setCanceledOnTouchOutside(false);
			mDialog.setCancelable(false);
			mDialog.setProgress(0);

			// import android.content.DialogInterface.OnClickListener;
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

			try {

				// By creating a request, we get a handle to the putFile
				// operation,
				// so we can cancel it later if we want to
				FileInputStream fis = new FileInputStream(mFile);
				String path = mPath + mFile.getName();
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

				if (mRequest == null) {
					mErrorMsg = "LiveFinder Request Failure. Please try again.";
					return "";
				}

				Entry ent = mRequest.upload();
				String picLinkPath = ent.path;
				DropboxLink shareLink = mApi.share(picLinkPath);
				String shareAddress = getShareURL(shareLink.url).replaceFirst(
						"https://www", "https://dl");
				int lastPos = shareAddress.lastIndexOf("?");
				String realShareAddress = shareAddress;
				if (lastPos != -1)
					realShareAddress = realShareAddress.substring(0, lastPos);

				String urlLiveFinderRequest = String.format(
						GlobalDefine.SiteURLs.liveFinderRequestURL,
						realShareAddress, mUserId);
				mDialogMsg = "Sending Request for LiveFinder...";
				publishProgress((long)100);
				
				// This method for HttpConnection
				BufferedReader bufferedReader = null;
				try {
					HttpClient client = new DefaultHttpClient();
					client.getParams().setParameter(
							CoreProtocolPNames.USER_AGENT, "android");
					HttpGet request = new HttpGet();
					request.setHeader("Content-Type",
							"text/plain; charset=utf-8");
					request.setURI(new URI(urlLiveFinderRequest));
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
					mErrorMsg = "LiveFinder Request Failure. Please check your connection with Server.";
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
			return "";
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
			mDialog.setMessage(mDialogMsg);
			mDialog.setProgress(percent);
		}

		@Override
		protected void onPostExecute(String result) {
			mDialog.dismiss();
			String strResultMsg = mErrorMsg;
			String strResultTitle = "LiveFinder Request Failure!";
			
			if (!result.isEmpty()) {
				result = result.trim();
				JSONObject requestResult;
				try {
					requestResult = new JSONObject(result);
					if (requestResult != null) {
						// Login status
						String strLoginStatus = requestResult.getString("status");
						int loginStatus = Integer.parseInt(strLoginStatus);

						if (loginStatus == 200) {
							String strlfid = requestResult.getString("lfid");
							int lfid = Integer.parseInt(strlfid);
							
							// start LiveFinderUpdateCheckingTask
							new LiveFinderUpdateCheckingTask(LiveFinderActivity.this, lfid).start();
							strResultTitle = "LiveFinder Request Sent!";
							strResultMsg = "Your request was successfully sent.\r\nWe will send you an update in a few minutes with your livefinder results.";
						}
					}
				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
					strResultMsg = e.getMessage();
				}
			} 
			
			AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(
					LiveFinderActivity.this);
			alertDialogBuilder
					.setTitle(strResultTitle)
					.setMessage(strResultMsg)
					.setCancelable(false)
					.setPositiveButton("Ok",
							new DialogInterface.OnClickListener() {
								public void onClick(DialogInterface dialog,
										int id) {
									dialog.cancel();
								}
							});
			AlertDialog alertDialog = alertDialogBuilder.create();
			alertDialog.show();
		}

		private void showToast(String msg) {
			Toast error = Toast.makeText(mContext, msg, Toast.LENGTH_LONG);
			error.show();
		}
	}

	// About Dropbox
	private AndroidAuthSession buildSession() {
		AppKeyPair appKeyPair = new AppKeyPair(APP_KEY, APP_SECRET);

		AndroidAuthSession session = new AndroidAuthSession(appKeyPair);
		loadAuth(session);
		return session;
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

	private void logOut() {
		// Remove credentials from the session
		mApi.getSession().unlink();

		// Clear our stored keys
		clearKeys();
		// Change UI state to display logged out version
		setLoggedIn(false);
	}

	private void clearKeys() {
		SharedPreferences prefs = getSharedPreferences(ACCOUNT_PREFS_NAME, 0);
		Editor edit = prefs.edit();
		edit.clear();
		edit.commit();
	}

	/**
	 * Convenience function to change UI state based on being logged in
	 */
	private void setLoggedIn(boolean loggedIn) {
		mLoggedIn = loggedIn;
		if (loggedIn) {
			mSubmit.setText(getString(R.string.title_unlink_dropbox));
			mDisplay.setVisibility(View.VISIBLE);
			// Create our Preview view and set it as the content of our
			// activity.
			boolean opened = safeCameraOpenInView(getWindow().getDecorView());

			if (opened == false) {
				Log.d("StopListing", "Error, Camera failed to open");
				showToast("Error, Camera failed to open");
				finish();
			}
		} else {
			mSubmit.setText(getString(R.string.title_link_dropbox));
			mDisplay.setVisibility(View.GONE);
			releaseCameraAndPreview();
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
}
