#include<iostream>
using namespace std;
#include<math.h>
#include<algorithm>
#include<vector>
class device{
	public:
	double data_rate, distance, tolerance;
	int dev_id;
	int allocation;
	device(int d_id, double d_rate, double dis, double tol, int allo){ //constructor for device
        data_rate=d_rate;
        dev_id=d_id;
        distance=dis;
        tolerance=tol;
        allocation=allo;
    }
    void output()
	{
		cout<<"\n"<<dev_id<<"          "<<data_rate<<"           "<<distance<<"      "<<tolerance<<"      "<<allocation;
	}
};
float In(int dis1,int dis2){ //function for calculating interference
    
    float value=dis1/dis2;
    return pow(value,4);
    
}
bool cmp(device oa, device ob){
    return (oa.data_rate < ob.data_rate); 
    }

int main()
{
    int d_id,p,n;
    double tol=0.0, d_rate, dis;
	vector<device> A;
	int k;
	cout<<"enter no of channels ";
	cin>>k;
    cout<<"\n Enter number of device:";
    cin>>n;
    cout<<"\n Enter number of device which have data rate greater than 20:";
    cin>>p;
    cout<<"\n Please enter first "<<p<< " devices(data rate>20 mbps) then enter rest of the devices: ";
	for(int i = 0; i <n; i++){
        double temp=0;
        cout<<"Enter for device: "<<(i+1)<<"\n";
        cout<<"device id:";
        cin>>d_id;
        cout<<"data rate:";
        cin>>d_rate;
        cout<<"distance:";
        cin>>dis;
        temp=d_rate/20;
		tol=1/(pow(2, temp)-1);
        int allo=0;
        A.push_back(*(new device(d_id, d_rate, dis, tol, allo)));
    }
    cout<<"\n Entered data:";
    cout<<"\ndevice_id data_rate distance tolerance allocation";
    for(int z=0;z<n;z++)
	{
		A[z].output();
	}

    sort(A.begin()+p, A.end(),cmp);
        cout<<"\n After sorting the devcies with data rate lesser tahn 20 mbps w.r.t data rate:";
        cout<<"\ndevice_id data_rate distance tolerance allocation";
       for(int z=0;z<n;z++)
	{
		A[z].output();
	}
    for(int i=0;i<n;i++)
	{
        if(i<p){
		A[i].allocation=i+1;
        }
        else{
            A[i].allocation=0;
        }
	}
    cout<<"\n After initial allocation";
    cout<<"\ndevice_id data_rate distance tolerance allocation";
    for(int z=0;z<n;z++)
	{
		A[z].output();
	}
    cout<<"\n starting algorithm";
    for(int i=p;i<n;i++){
        for(int c=1;c<=k;c++){
            A[i].allocation=c;
            vector<device> D;
            //making a set of those device which are allocated to same channel
            for(int x=0;x<n;x++){
                if(A[x].allocation==c){
                    D.push_back(*(new device(A[x].dev_id, A[x].data_rate, A[x].distance, A[x].tolerance, A[x].allocation)));
                }
            }
            for (int x=0;x<D.size();x++)
            {
                double sum=0.0;
                for (int y=0;y<D.size();y++)
                {   
                    if(x==y){
                        sum=sum+0.0;
                    }
                    else{
                    sum=sum+In(D[x].distance,D[y].distance);
                    }
                }
                //checking if sum of interference greater than tolerance
                if (sum>D[x].tolerance){
                    A[i].allocation=0;
                    break;
                }
                
            }
            if(A[i].allocation==c)
            break;
            
        }

    }
    cout<<"\n Done \n";
    cout<<"\ndevice_id data_rate distance tolerance allocation";
    for(int z=0;z<n;z++)
	{
		A[z].output();
	}

}