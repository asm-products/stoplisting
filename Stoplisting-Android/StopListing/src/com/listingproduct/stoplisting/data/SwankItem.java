package com.listingproduct.stoplisting.data;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;

import com.listingproduct.stoplisting.global.GlobalDefine;

import android.text.format.DateFormat;

public class SwankItem {

	String mTitle;
	String mImageUrl;
	String mSoldDate;
	String mSoldPrice;

	/* constructor */
	public SwankItem() {
		mTitle = "";
		mImageUrl = "";
		mSoldDate = "";
		mSoldPrice = "";
	}

	public SwankItem(String title, String imageUrl, String soldDate,
			String soldPrice) {
		mTitle = title;
		mImageUrl = imageUrl;
		mSoldDate = soldDate; // is "dd/MM/yyyy", not "yyyy-MM-dd"
		mSoldPrice = "$" + soldPrice;
	}

	/* set and get Item Photo url */
	public void setTitle(String title) {
		mTitle = title;
	}

	public String getTitle() {
		return mTitle;
	}

	/* set and get Item Price */
	public void setImageUrl(String imageUrl) {
		mImageUrl = imageUrl;
	}

	public String getImageUrl() {
		return mImageUrl;
	}

	/* set and get Item date */
	public void setSoldDate(String soldDate) {
		
		SimpleDateFormat formatter1 = new SimpleDateFormat("yyyy-MM-dd");
		try {

			Date date = formatter1.parse(soldDate);
			Calendar thatDay = Calendar.getInstance();
			thatDay.setTime(date);
			int nMonth = thatDay.get(thatDay.MONTH);
			int nDayOfMonth = thatDay.get(thatDay.DAY_OF_MONTH);
			int nYear = thatDay.get(thatDay.YEAR);
			
			soldDate = String.format("%s %d, %d", GlobalDefine.MONTH_Title[nMonth], nDayOfMonth, nYear);

		} catch (ParseException e) {
			e.printStackTrace();
		}
		
		mSoldDate = soldDate;
	}

	public String getSoldDate() {
		return mSoldDate;
	}

	/* set and get Item state */
	public void setSoldPrice(String soldPrice) {
		mSoldPrice = "$" + soldPrice;
	}

	public String getSoldPrice() {
		return mSoldPrice;
	}
}
