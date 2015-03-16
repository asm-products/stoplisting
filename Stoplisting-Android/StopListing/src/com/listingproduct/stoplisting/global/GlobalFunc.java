package com.listingproduct.stoplisting.global;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

public class GlobalFunc {

	/**
	 * Check current state
	 * */
	private static boolean isDebugMode() {
		return GlobalDefine.CommonState.debug_mode;
	}

	/**
	 * Show log msg in the custom type. blockMode = true -> Block log msg.
	 * blockMode = false -> Show one row of log msg.
	 * */
	public static void viewLog(String tag, String msg, boolean blockMode) {

		// If no Debug State
		if (!isDebugMode())
			return;
		if (tag == null || msg == null)
			return;
		if (blockMode) {
			Log.e(tag, "/*------------------------------------------");
			Log.e(tag, msg);
			Log.e(tag, "------------------------------------------*/");
			Log.e(tag, " ");
		} else {
			Log.e(tag, msg);
		}
	}
}
