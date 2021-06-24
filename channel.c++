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
	device(int d_id, double d_rate, double dis, double tol, int allo){
        data_rate=d_rate;
        dev_id=d_id;
        distance=dis;
        tolerance=tol;
        allocation=allo;
    }
    void output()
	{
		cout<<"\n details are : "<<dev_id<<" "<<data_rate<<" "<<distance<<" "<<tolerance<<" "<<allocation;
	}
};
float In(int dis1,int dis2){
    
    float value=dis1/dis2;
    return pow(value,4);
    
}
bool cmp(device oa, device ob){
    return (oa.data_rate < ob.data_rate); 
    }

int main()
{
    int d_id,p=2;
    double sum=0.0,tol=0.0, d_rate, dis;
	vector<device> A;
	int k;
	cout<<"enter no of channels ";
	cin>>k;
	for(int i = 0; i <5; i++){
        double temp=0;
        cout<<"Enter for device: "<<(i+1)<<"\n";
        cin>>d_id>>d_rate>>dis;
        temp=d_rate/20;
		tol=1/(pow(2, temp)-1);
        int allo=0;
        A.push_back(*(new device(d_id, d_rate, dis, tol, allo)));
    }
    cout<<"\n Before sort";
    for(int z=0;z<5;z++)
	{
		A[z].output();
	}

    sort(A.begin()+p, A.end(),cmp);
        cout<<"\n AFter sort";
       for(int z=0;z<5;z++)
	{
		A[z].output();
	}
    for(int i=0;i<5;i++)
	{
        if(i<p){
		A[i].allocation=i+1;
        }
        else{
            A[i].allocation=0;
        }
	}
    cout<<"\n After initial allocation";
    for(int z=0;z<5;z++)
	{
		A[z].output();
	}
    cout<<"\n starting algo";
    for(int i=p;i<5;i++){
        for(int c=1;c<=k;c++){
            A[i].allocation=c;
            vector<device> D;
            for(int x=0;x<5;x++){
                if(A[x].allocation==c){
                    D.push_back(*(new device(A[x].dev_id, A[x].data_rate, A[x].distance, A[x].tolerance, A[x].allocation)));
                }
            }
            cout<<"\n values of D";
            for(int z=0;z<D.size();z++)
	        {
		    D[z].output();
	        }
            for (int x=0;x<D.size();x++)
            {
                for (int y=0;y<D.size();y++)
                {   
                    if(x==y){
                        sum=sum+0;
                    }
                    else{
                    sum=sum+In(D[x].distance,D[y].distance);
                    }
                }
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
    for(int z=0;z<5;z++)
	{
		A[z].output();
	}

}