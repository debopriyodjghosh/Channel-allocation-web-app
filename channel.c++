#include<iostream>
using namespace std;
#include<math.h>
#include<algorithm>
#include<vector>
class device{
	public:
	float data_rate, distance, tolerance;
	int dev_id;
	int allocation=0;
	device(int d_id, int d_rate, int dis){
        data_rate=d_rate;
        dev_id=d_id;
        distance=dis;
    }
    void caltol()
	
	{
		float temp=data_rate/20;
		tolerance=1/(pow(2, temp)-1);
		
		
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
    int d_id, d_rate, dis,p=2,r=1;
    float sum=0.0;
	vector<device> A;
	int i,k;
	cout<<"enter no of channels ";
	cin>>k;
	for(int i = 0; i <5; i++){
        cout<<"Enter for device: "<<(i+1)<<"\n";
        cin>>d_id>>d_rate>>dis;
        A.push_back(*(new device(d_id, d_rate, dis)));
    }
	for(device i : A)
	{
		i.caltol();
	}	
    sort(A.begin()+p, A.end(),cmp);
    for(int i=0;i<5;i++)
	{
        if(r<=p){
		A[i].allocation=r;
        r++;
        }
        else{
            A[i].allocation=0;
        }
	}
    for(int i=p;i<5;i++){
        for(int c=1;c<=k;c++){
            A[i].allocation=c;
            vector<device> D;
            int f=0;
            for(int x=0;x<5;x++){
                if(A[x].allocation==c){
                    D[f]=A[x];
                    f++;
                }
            }
            for (int x=0;x<D.size();x++)
            {
                for (int y=1;y<D.size();y++)
                {   
                    if(x==y){
                        sum=0;
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
    cout<<"Done \n";
    for(int z=0;z<5;z++)
	{
        cout<<"I am here";
		A[z].output();
	}

}