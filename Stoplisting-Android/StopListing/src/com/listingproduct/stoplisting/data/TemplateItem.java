package com.listingproduct.stoplisting.data;

public class TemplateItem {
	
	int mId;	
	String mTitle;
	String mImageUrl;
	int mUserId;
	
	/* constructor */
	public TemplateItem() {
		mId = -1;
		mUserId = 0;
		mTitle = "";
		mImageUrl = "";
	}
	
	public TemplateItem(int id, int userId, String planTitle, String planPrice) {
		mId = id;
		mUserId = userId; 
		mTitle = planTitle;
		mImageUrl = planPrice;		
	}
	
	/*set and get Plan Id*/
	public void setTempId(int id) {
		mId = id;
	}
	
	public int getTempId() {
		return mId;
	}
	
	/*set and get User id*/
	public void setUserId(int userid) {
		mUserId = userid;
	}
	public int getUserId() {
		return mUserId;
	}
	
	/*set and get Template Title*/
	public void setTitle(String title) {
		mTitle = title;
	}
	
	public String getTitle() {
		return mTitle;
	}
	
	/*set and get Template Url*/
	public void setImageUrl(String imgUrl) {
		mImageUrl = imgUrl;
	}
	public String getImageUrl() {
		return mImageUrl;
	}
}
