package com.listingproduct.stoplisting.data;

import java.io.File;

public class DropboxUrlItem {
	public File mLocalFile;
	public String mLinkUrl;
	
	public DropboxUrlItem() {
		mLocalFile = null;
		mLinkUrl = null;
	}
	
	public DropboxUrlItem(File file, String url) {
		mLocalFile = file;
		mLinkUrl = url;
	}
}

