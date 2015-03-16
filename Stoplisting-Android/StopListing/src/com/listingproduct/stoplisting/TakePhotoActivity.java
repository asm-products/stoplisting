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
import java.text.DateFormat;
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
import com.dropbox.client2.exception.DropboxException;
import com.dropbox.client2.exception.DropboxFileSizeException;
import com.dropbox.client2.exception.DropboxIOException;
import com.dropbox.client2.exception.DropboxParseException;
import com.dropbox.client2.exception.DropboxPartialFileException;
import com.dropbox.client2.exception.DropboxServerException;
import com.dropbox.client2.exception.DropboxUnlinkedException;
import com.listingproduct.stoplisting.global.GlobalDefine;
import com.listingproduct.stoplisting.global.GlobalFunc;
import com.listingproduct.stoplisting.utilities.DialogHelper;
import com.listingproduct.stoplisting.utilities.NetStateUtilities;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.ActivityNotFoundException;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.DialogInterface.OnClickListener;
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
import android.provider.MediaStore;
import android.provider.MediaStore.Images.Media;
import android.util.Log;
import android.view.Display;
import android.view.Gravity;
import android.view.Surface;
import android.view.SurfaceHolder;
import android.view.SurfaceView;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.listingproduct.stoplisting.utilities.ImageUtils;

public class TakePhotoActivity extends MyBaseActivity {

	// Native camera.
	private Camera mCamera;

	// View to display the camera output.
	private CameraPreview mPreview;

	// Reference to the containing view.
	private View mCameraView;

	// Image Count TextView
	private TextView imgCntTv;
	// Thumb ImageView
	private ImageView mthumbIv;

	private ArrayList<String> itemPhotoPathList = new ArrayList<String>();

	public Typeface font;
	String Tag = "stTakePhotoActivity";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.takephoto_screen);

		//showToast("Enter onCreate()");

		// load Font
		font = ((Application) getApplication()).getFont();

		// Close button
		Button closeBtn = (Button) findViewById(R.id.closeBtn);
		closeBtn.setTypeface(font);
		closeBtn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				finish();
			}
		});

		//showToast("onCreate()_1");

		FrameLayout cancelPictureBtn = (FrameLayout) findViewById(R.id.cancelPicBtn);
		TextView picTv = (TextView) findViewById(R.id.pictureTv);
		TextView cancelTv = (TextView) findViewById(R.id.cancelTv);
		picTv.setTypeface(font);
		cancelTv.setTypeface(font);
		cancelPictureBtn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				// Show cancel dialog
				ShowCancelPhotoDlg();
			}
		});

		//showToast("onCreate()_2");

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

		//showToast("onCreate()_3");

		// Upload button
		Button uploadBtn = (Button) findViewById(R.id.uploadBtn);
		uploadBtn.setTypeface(font);
		uploadBtn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				// upload Current Item Photos
				uploadCurItemPhotos();
			}
		});

		//showToast("onCreate()_4");

		// Create our Preview view and set it as the content of our activity.
		boolean opened = safeCameraOpenInView(getWindow().getDecorView());

		if (opened == false) {
			Log.d("StopListing", "Error, Camera failed to open");
		}

		//showToast("onCreate()_5");
		// Current Item Photo Count TextView
		imgCntTv = (TextView) findViewById(R.id.imgCntTv);

		// ThumbView
		mthumbIv = (ImageView) findViewById(R.id.thumbIv);
		mthumbIv.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				// show current Item Photos
				if (itemPhotoPathList != null && itemPhotoPathList.size() > 0) {
					Intent showPhotsIntent = new Intent(TakePhotoActivity.this,
							ItemPhotosViewActivity.class);
					showPhotsIntent.putExtra("filelist", itemPhotoPathList);
					startActivity(showPhotsIntent);
				}
			}
		});

		//showToast("Leave onCreate()");
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
			File pictureFile = getOutputMediaFile();
			if (pictureFile == null) {
				Toast.makeText(TakePhotoActivity.this,
						"Image retrieval failed.", Toast.LENGTH_SHORT).show();
				return;
			}

			try {
				FileOutputStream fos = new FileOutputStream(pictureFile);
				fos.write(data);
				fos.close();

				// Update Thumb Image
				setFullImageFromFilePath(pictureFile.getAbsolutePath(),
						mthumbIv);
				// Add new File path
				itemPhotoPathList.add(pictureFile.getAbsolutePath());
				GlobalFunc.viewLog(Tag, pictureFile.getAbsolutePath(), true);

				// Update Top title
				String strTopTitle = String.format("%s",
						itemPhotoPathList.size());
				imgCntTv.setText(strTopTitle);

				// Restart the camera preview.
				safeCameraOpenInView(mCameraView);
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

	private void uploadCurItemPhotos() {
		if (itemPhotoPathList == null || itemPhotoPathList.size() == 0) {
			showToast("There is no Photos for uploading new item.");
			return;
		}

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

		((Application) getApplication()).listNewitem(itemPhotoPathList);
		resetItem();

		/*
		 * DropboxAPI<AndroidAuthSession> mApi; mApi = ((Application)
		 * getApplication()).getDropboxApi();
		 * 
		 * UploadPicture upload = new UploadPicture(this, mApi, PHOTO_DIR,
		 * itemPhotoPathList, null); upload.execute();
		 */
	}

	public class UploadPicture extends AsyncTask<Void, Long, Boolean> {

		private DropboxAPI<?> mApi;

		private String mPath;
		private File mFile;
		private long mFileLen;

		private UploadRequest mRequest;
		private Context mContext;
		private final ProgressDialog mDialog;
		private String mDialogMsg;

		private String mErrorMsg;

		private ArrayList<String> mFileList;
		private ArrayList<String> mShareLinkList;

		private String TAG = "UploadPicture";

		public UploadPicture(Context context, DropboxAPI<?> api,
				String dropboxPath, ArrayList<String> fileList, File file) {
			// We set the context this way so we don't accidentally leak
			// activities
			mContext = context.getApplicationContext();

			mApi = api;
			mPath = dropboxPath;
			mFile = file;
			mFileList = fileList;
			mShareLinkList = new ArrayList<String>();

			mDialog = new ProgressDialog(context);
			mDialog.setMax(100);
			mDialog.setMessage("Uploading files to DropBox");
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
		protected Boolean doInBackground(Void... params) {

			try {

				for (int i = 0; i < mFileList.size(); i++) {

					String curFilePath = mFileList.get(i);
					mFile = new File(curFilePath);

					// check file exist
					if (!mFile.exists())
						continue;

					if (i == 0) {
						mDialogMsg = String.format(
								"Uploading 1st/%d file to Dropbox",
								mFileList.size());
					} else if (i == 1) {
						mDialogMsg = String.format(
								"Uploading 2nd/%d file to Dropbox",
								mFileList.size());
					} else if (i == 2) {
						mDialogMsg = String.format(
								"Uploading 3rd/%d file to Dropbox",
								mFileList.size());
					} else {
						mDialogMsg = String.format(
								"Uploading %dth/%d file to Dropbox", i + 1,
								mFileList.size());
					}

					mFileLen = mFile.length();
					publishProgress((long) 0);

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

					if (mRequest != null) {
						Entry ent = mRequest.upload();
						String picLinkPath = ent.path;
						DropboxLink shareLink = mApi.share(picLinkPath);
						String shareAddress = getShareURL(shareLink.url)
								.replaceFirst("https://www", "https://dl");
						mShareLinkList.add(shareAddress);
					}
				}

				return true;

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
			return false;
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
		protected void onPostExecute(Boolean result) {
			mDialog.dismiss();
			if (result) {
				uploadNewItem(mShareLinkList);
			} else {
				showToast(mErrorMsg);
			}
		}

		private void showToast(String msg) {
			Toast error = Toast.makeText(mContext, msg, Toast.LENGTH_LONG);
			error.show();
		}
	}

	private void uploadNewItem(ArrayList<String> shareLinkList) {
		if (shareLinkList == null || shareLinkList.size() == 0) {
			return;
		}

		String urlListingItem = GlobalDefine.SiteURLs.listingURL;
		for (int i = 0; i < shareLinkList.size(); i++) {
			String shareLink = shareLinkList.get(i);
			if (i > 0)
				urlListingItem += "-";
			urlListingItem += shareLink;
		}

		GlobalFunc.viewLog(Tag, urlListingItem, true);
		showToast(urlListingItem);

		resetItem();
		// new listItemTask(this).execute(urlListingItem);
		// new listItemTask(this).execute("");
	}

	// AsyncTask<Params,Progress,Result>
	private class listItemTask extends AsyncTask<String, Void, String> {

		private ProgressDialog mDlg;
		private Context mContext;

		public listItemTask(Context context) {
			mContext = context;
		}

		@Override
		protected void onPreExecute() {
			mDlg = new ProgressDialog(mContext);
			mDlg.setProgressStyle(ProgressDialog.STYLE_SPINNER);
			mDlg.setTitle("Please wait.");
			mDlg.setMessage("Listing new item...");
			mDlg.setCanceledOnTouchOutside(false);
			mDlg.setCancelable(false);
			mDlg.show();
			super.onPreExecute();
		}

		@Override
		protected String doInBackground(String... urls) {

			if (urls[0].isEmpty())
				return "";

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

		@Override
		protected void onPostExecute(String result) {
			boolean listingSuccess = false;
			String errorMsg = "";

			if (result != null && !result.isEmpty()) {
				try {
					JSONArray loginJson = new JSONArray(result);
					JSONObject loginResult = loginJson.getJSONObject(0);

					if (loginResult != null) {
						// Login status
						String strLoginStatus = loginResult.getString("status");
						int loginStatus = Integer.parseInt(strLoginStatus);

						if (loginStatus == 200) {

							// successfully listed
							listingSuccess = true;
						} else if (loginStatus == 503) {
							// Invalide user info
							listingSuccess = false;
							errorMsg = "Listing Failure! \n Please try again.";
						} else {
							// Unknown error
							listingSuccess = false;
							errorMsg = "Listing Failure! \n Please try again.";
						}
					} else {
						// Unknown error
						listingSuccess = false;
						errorMsg = "Listing Failure! \n There is no listing result. Please try again.";
					}
				} catch (JSONException e) {
					// Json Parsing error
					listingSuccess = false;
					errorMsg = "Login Failure! \n Please try again.";
				}
			} else {
				// Network error
				listingSuccess = false;
				errorMsg = "Listing Failure! \nPlease confirm your network connection with server.";
			}

			mDlg.dismiss();

			if (listingSuccess) {
				// success login and goto next page
				showToast("Successfully listed new item.");
				resetItem();
			} else {
				// Failed login
				GlobalFunc.viewLog(Tag, "Cannot list new item.", true);
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

	private void ShowCancelPhotoDlg() {

		final Dialog myDialog = new Dialog(TakePhotoActivity.this,
				R.style.CustomTheme);

		myDialog.setContentView(R.layout.cancel_photo_option_dialog);
		myDialog.setTitle("Choose Library");
		TextView takePhotoBtn = (TextView) myDialog
				.findViewById(R.id.delPrevPhotoBtn);

		takePhotoBtn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				deletePrevPhoto();
				myDialog.dismiss();
			}
		});

		TextView chooseFromBtn = (TextView) myDialog
				.findViewById(R.id.cancelCurPhotoSetBtn);
		chooseFromBtn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				cancelCurrentPhotoSet();
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

	private void deletePrevPhoto() {

		if (itemPhotoPathList != null && itemPhotoPathList.size() > 0) {
			int nPhotos = itemPhotoPathList.size();

			// erase last photo
			String strFilePath = itemPhotoPathList.get(nPhotos - 1);
			File file = new File(strFilePath);
			if (file.exists())
				file.delete();

			itemPhotoPathList.remove(nPhotos - 1);
			--nPhotos;
			if (nPhotos > 0) {
				strFilePath = itemPhotoPathList.get(nPhotos - 1);
				// Update Thumb Image with prev file
				setFullImageFromFilePath(strFilePath, mthumbIv);
			} else {
				mthumbIv.setImageBitmap(null);
			}
			// Update Top title
			String strTopTitle = String.format("%s", nPhotos);
			imgCntTv.setText(strTopTitle);
		}
	}

	private void cancelCurrentPhotoSet() {
		if (itemPhotoPathList != null && itemPhotoPathList.size() > 0) {
			// erase all photo
			for (String filePath : itemPhotoPathList) {
				File file = new File(filePath);
				if (file.exists())
					file.delete();
			}

			itemPhotoPathList.clear();
			mthumbIv.setImageBitmap(null);

			// Update Top title
			String strTopTitle = String.format("%d", 0);
			imgCntTv.setText(strTopTitle);
		}
	}

	private void resetItem() {
		itemPhotoPathList = new ArrayList<String>();
		// Update Top title
		String strTopTitle = String.format("%s", itemPhotoPathList.size());
		imgCntTv.setText(strTopTitle);
		mthumbIv.setImageBitmap(null);
	}
}
