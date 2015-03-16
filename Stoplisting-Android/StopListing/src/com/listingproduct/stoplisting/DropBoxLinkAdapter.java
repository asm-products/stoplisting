package com.listingproduct.stoplisting;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.util.ArrayList;

import com.listingproduct.stoplisting.data.DropboxUrlItem;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.GridView;
import android.widget.ImageView;

public class DropBoxLinkAdapter extends ArrayAdapter<DropboxUrlItem> {
	private int selectedIndex;
	private Context context;
	private ArrayList<DropboxUrlItem> itemList;
	private int imgWidth;
	private int imgHeight;
	
	public DropBoxLinkAdapter(Context ctx, int resource, ArrayList<DropboxUrlItem> objects) {
		super(ctx, resource, objects);
		context = ctx;
		itemList = objects;
		selectedIndex = -1;
		imgWidth = context.getResources().getDimensionPixelSize(R.dimen.
				upload_pic_grid_width);
		imgHeight = context.getResources().getDimensionPixelSize(R.dimen.
				upload_pic_grid_height);
	}

	public void setSelectedIndex(int position) {
		selectedIndex = position;
		notifyDataSetChanged();
	}

	public DropboxUrlItem getSelectedItem() {
		return itemList.get(selectedIndex);
	}

	@Override
	public int getCount() {
		return itemList.size();
	}

	@Override
	public DropboxUrlItem getItem(int position) {
		return itemList.get(position);
	}

	@Override
	public long getItemId(int position) {
		return position;
	}

	public void replaceItems(ArrayList<DropboxUrlItem> list) {
		itemList = list;
		notifyDataSetChanged();
	}

	public void addNewItem(DropboxUrlItem newItem) {
		if (itemList  == null) {
			itemList = new ArrayList<DropboxUrlItem>();
		} 
		itemList.add(newItem);
		notifyDataSetChanged();
	}
	
	public ArrayList<DropboxUrlItem> getFileList() {
		return itemList;
	}
	
	@Override
	public View getView(int position, View convertView, ViewGroup parent) {
		ImageView imageView;
		
		if (convertView == null) {
			imageView = new ImageView(context);
		} else {
			imageView = (ImageView) convertView;
		}
		
		int padding = 3;
		GridView.LayoutParams param = new GridView.LayoutParams(imgWidth + padding * 2, imgHeight + padding * 2);
		imageView.setLayoutParams(param);
		imageView.setScaleType(ImageView.ScaleType.CENTER_CROP);
		imageView.setPadding(padding, padding, padding, padding);
		
		DropboxUrlItem item = itemList.get(position);
		//imageView.setImageURI(Uri.fromFile(item.mLocalFile));
		imageView.setImageBitmap(decodeFile(item.mLocalFile, imgWidth, imgHeight));
		return imageView;
	}
	
	//decodes image and scales it to reduce memory consumption
	private Bitmap decodeFile(File f, int nReqWidth, int nReqHeight){
	    try {
	        //Decode image size
	        BitmapFactory.Options o = new BitmapFactory.Options();
	        o.inJustDecodeBounds = true;
	        BitmapFactory.decodeStream(new FileInputStream(f),null,o);

	        //Find the correct scale value. It should be the power of 2.
	        int scale=1;
	        while(o.outWidth/scale/2>=nReqWidth && o.outHeight/scale/2>=nReqHeight)
	            scale*=2;
	        
	        //The new size we want to scale to
	        //final int REQUIRED_SIZE=70;
	        //while(o.outWidth/scale/2>=REQUIRED_SIZE && o.outHeight/scale/2>=REQUIRED_SIZE)
	        //	scale*=2;
	        
	        //Decode with inSampleSize
	        BitmapFactory.Options o2 = new BitmapFactory.Options();
	        o2.inSampleSize=scale;
	        return BitmapFactory.decodeStream(new FileInputStream(f), null, o2);
	    } catch (FileNotFoundException e) {}
	    return null;
	}
}