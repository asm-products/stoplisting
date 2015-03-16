package com.listingproduct.stoplisting.data;

public class StopListingItem {
	
	String mTitle;
	String mPhotoUrl;
	float mPrice;
	String mDate;
	String mState;
	String mFormat;
	int mDuration;
	int mPromoted;
	String mTemplate;
	String mUiId;

	/* constructor */
	public StopListingItem() {
		mTitle = "";
		mPhotoUrl = "";
		mPrice = 0;
		mDate = "";
		mState = "";
		mFormat = "";
		mDuration = 0;
		mPromoted = 0;
		mTemplate = "";
		mUiId = "";
	}

	public StopListingItem(String title, String photoUrl, float price, String date, String state,
			String format, int duration, int promoted,
			String template) {
		
		mTitle = title;
		mPhotoUrl = photoUrl;
		mPrice = price;
		mDate = date;
		mState = state;
		mFormat = format;
		mDuration = duration;
		mPromoted = promoted;
		mTemplate = template;
	}

	/* set and get Item Title */
	public void setTitle(String itemTitle) {
		mTitle = itemTitle;
	}
	public String getTitle() {
		return mTitle;
	}
	
	/* set and get Item Photo url */
	public void setPhotoUrl(String itemPhotoUrl) {
		mPhotoUrl = itemPhotoUrl;
	}
	public String getPhotoUrl() {
		return mPhotoUrl;
	}
	
	/* set and get Item Price */
	public void setPrice(float itemPrice) {
		mPrice = itemPrice;
	}
	public float getPrice() {
		return mPrice;
	}

	/* set and get Item date */
	public void setDate(String itemdate) {
		mDate = itemdate;
	}
	public String getDate() {
		return mDate;
	}
	
	/* set and get Item state */
	public void setState(String itemState) {
		mState = itemState;
	}
	public String getState() {
		return mState;
	}

	/* set and get Item Format */
	public void setFormat(String itemFormat) {
		mFormat = itemFormat;
	}
	public String getFormat() {
		return mFormat;
	}

	/* set and get Item Duration */
	public void setDuration(int itemDuration) {
		mDuration = itemDuration;
	}
	public int getDuration() {
		return mDuration;
	}
	
	/* set and get Item Prompted */
	public void setPrompted(int itemPrompted) {
		mPromoted = itemPrompted;
	}
	public int getPrompted() {
		return mPromoted;
	}
	
	/* set and get Item Template */
	public void setTemplate(String itemTemplate) {
		mTemplate = itemTemplate;
	}
	public String getTemplate() {
		return mTemplate;
	}
	
	/* set and get Item Ui_Id */
	public void setUiId(String uiId) {
		mUiId = uiId;
	}
	public String getUiId() {
		return mUiId;
	}
}
