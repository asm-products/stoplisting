package com.listingproduct.stoplisting.data;

public class Plan {
	
	int mId;
	String mPlanTitle;
	int mPlanPrice;
	int mPlanListingCnt;
	
	/* constructor */
	public Plan() {
		mId = -1;
		mPlanTitle = "";
		mPlanPrice = 0;
		mPlanListingCnt = 0; 
	}
	
	public Plan(int id, String planTitle, int planPrice, int planListingCnt) {
		mId = id;
		mPlanTitle = planTitle;
		mPlanPrice = planPrice;
		mPlanListingCnt = planListingCnt; 
	}
	
	/*set and get Plan Id*/
	public void setPlanId(int id) {
		mId = id;
	}
	
	public int getPlanId() {
		return mId;
	}
	
	/*set and get Plan Title*/
	public void setPlanTitle(String planTitle) {
		mPlanTitle = planTitle;
	}
	
	public String getPlanTitle() {
		return mPlanTitle;
	}
	
	/*set and get Plan Price*/
	public void setPlanPrice(int planPrice) {
		mPlanPrice = planPrice;
	}
	public int getPlanPrice() {
		return mPlanPrice;
	}
	
	/*set and get Plan listing count*/
	public void setPlanListingCount(int planListingCnt) {
		mPlanListingCnt = planListingCnt;
	}
	public int getPlanListingCount() {
		return mPlanListingCnt;
	}
}
