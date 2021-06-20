#include<iostream>
using namespace std;
#include<math.h>
class device{
	float data_rate, distance, tolerance;
	int dev_id;
	int allocation;
	public:
	
	 void input()
	{
		cout<<"enter device id, data rate and distance : ";
		cin>>dev_id;
		cin>>data_rate;
		cin>>distance;
	}
	
	 void output()
	{
		cout<<"details are : "<<dev_id<<data_rate<<distance<<tolerance;
	}
	void caltol()
	
	{
		float temp=data_rate/20;
		tolerance=1/(pow(2, temp)-1);
		
		
	}
	
};


int main()
{
	device A[5];
	int i,k;
	cout<<"enter no of channels ";
	cin>>k;
	for(i=0;i<=5;i++)
	{
		cout<<"enter "<<i<<"th device ";
			A[i].input();
	}
	for(i=0;i<=5;i++)
	{
		A[i].caltol();
	}
	for(i=0;i<=5;i++)
	{
		cout<<i<<"th device is /n";
			A[i].output();
	}

	
}



