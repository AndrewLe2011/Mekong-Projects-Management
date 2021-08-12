# Projects Management App

This is a ***PHP*** web application which is utilized to manage ***Mekong Power Generation Infrastructures*** by performing ***CRUD operations on CSV file*** and ***displaying data as a dataset from a hosting database.***

## Author
Full Name: ***Le Nguyen Truong An***
Student ID: ***s3820098***
Location: ***Ho Chi Minh, Vietnam***

## Status
Last Updated: ***Thursday, August 12, 2021  23:30:00 (GMT+7)***

## Usage
Users can launch this web application on browser by clicking: [Projects Management App](https://asm1cc21b.et.r.appspot.com/)

## Screenshot of GUI
![Home Page](https://i.imgur.com/shNvrBc.png)

## Technology Used
Programming Languages: ***PHP 7.4.0***, ***HTML5*** ,***CSS***, ***JavaScript***, ***Standard SQL***

***Google Cloud SDK 351.0.0:*** 
- ***Google App Engine***
- ***Google Bucket*** of ***Google Cloud Storage v1.24.1***
- ***Google BigQuery*** of ***Google Cloud BigQuery v1.22.1***

Dependency Management:  for ***PHP*** -> ***Composer v2.1.3***

Other: 
- Styling: ***Bootstrap v4.6.0***
- Functionality: ***jQuery v3.5.1***, ***Datatables.js v.1.10.5***

## Self-evaluation Checklist
- **Functional Requirements:**
	Home Page:
	1. Upload [data file](https://data.vietnam.opendevelopmentmekong.net/dataset/mekong-infrastructure-tracker/resource/9640d37d-53ca-42fb-83a0-04de89228f1d) to Google Bucket with the name of ***project.csv*** ✅
	2. Peform Create, Update, Delete operations ✅
	
	Querying Page:
	3. Upload [data file](https://data.vietnam.opendevelopmentmekong.net/dataset/mekong-infrastructure-tracker/resource/9640d37d-53ca-42fb-83a0-04de89228f1d) to Google BigQuery as a dataset ✅
	4. Display all the projects information ✅
	5.  Pagination: 
		- Choose page size + default as 10 ✅
		- Choose page number ✅
		- Jump to Next and Previous page ✅
		- Jump to First and Last page ✅
	6. Searching and Filtering:
		- Search projects by Name (Input Area) ✅
		- Filter projects by Country (Selection Options) ✅
- **Non-Functional Requirements:**
	1. Responsive GUI with proper styling ✅
	2. Validate input data when performing Create and Update ✅

- **Other Special Notices:**
	* When launching on Safari, web browser for IOS mobile devices, if it is first time launching, the application will load fully normally. But if hitting refresh or performing any operations required refreshing, the contents might not be rendered properly. 
	* But otherwise, the application performs properly on almost any modern computers and laptops browser.

## API Reference
- **Styling**
	- [bootstrap.min.css](https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css)
	- [jquery.dataTables.min.css](https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css)
	
- **JavaScript**
	- [jquery-3.5.1.js](https://code.jquery.com/jquery-3.5.1.js)
	- [jquery-3.5.1.slim.min.js](https://code.jquery.com/jquery-3.5.1.slim.min.js)
	- [popper.min.js](https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js)
	- [bootstrap.min.css](https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js)
	- [bootstrap.bundle.min.js](https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js)
	- [jquery.dataTables.min.js](https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js)
	- 
## License
2021 GitHub, Inc. © [AndrewLe2011](https://github.com/AndrewLe2011)