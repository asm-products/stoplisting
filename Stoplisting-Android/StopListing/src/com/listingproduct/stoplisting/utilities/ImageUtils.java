package com.listingproduct.stoplisting.utilities;

import android.graphics.Bitmap;
import android.graphics.Matrix;

public class ImageUtils {
	
	public static Bitmap rotateBitmap(Bitmap bitmap, int angle){
		// Getting width & height of the given image.
		int w = bitmap.getWidth();
		int h = bitmap.getHeight();
		
		// Setting pre rotate
		Matrix mtx = new Matrix();
		mtx.preRotate(angle);

		// Rotating Bitmap
		bitmap = Bitmap.createBitmap(bitmap, 0, 0, w, h, mtx, false);
		return bitmap;
	}
}
