# **Greedy Based Channel Allocation Strategy**

## **Abstract**  
Channel allocation schemes are crucial in mobile networks for distributing bandwidth and channels to mobile stations. The primary goal is to achieve maximum efficiency through channel reuse while avoiding interference among nearby cells or networks sharing the bandwidth.

---

## **Introduction**  
Channel allocation involves dividing a single channel into sub-channels and assigning them to multiple users to handle user-specific tasks. The number of users may vary with each process. If there are \( N \) users, the channel is divided into \( N \) equal-sized sub-channels, with each user receiving one portion.  

For a small, static number of users, **Frequency Division Multiplexing (FDM)** is an effective channel allocation technique due to its simplicity and efficiency. Channel allocation problems can be solved using two primary schemes:  

1. **Static Channel Allocation (FCA)**
2. **Dynamic Channel Allocation (DCA)**  

### **Categories of Channel Allocation Schemes**  
Depending on how co-channels are separated, channel allocation schemes can be classified into:  

- **A. Fixed Channel Allocation (FCA)**  
- **B. Dynamic Channel Allocation (DCA)**  

---

## **Fixed Channel Allocation (FCA)**  

### **Working Principle**  
In FCA, the total bandwidth is divided into \( N \) discrete channels using **Frequency Division Multiplexing (FDM)**. Each channel is assigned to a single user. Typically, channels are of equal size, ensuring that each user has a private frequency band.  

### **Advantages**  
- Suitable for a small number of fixed users with steady, uniform traffic.  
- Simple implementation with minimal overhead for complex algorithms.  
- No interference between users due to dedicated channels.  

### **Disadvantages**  
- Inefficient for real-life networks with a large, variable number of users and bursty traffic.  
- Limited bandwidth for individual users when \( N \) is large, reducing throughput.  
- Bandwidth assigned to inactive users is wasted.  
- Denial of service occurs when the number of users exceeds \( N \), even if some channels are unused.  

---

## **Dynamic Channel Allocation (DCA)**  

### **Working Principle**  
In DCA, frequency bands are not permanently assigned to users. Instead, channels are dynamically allocated from a central pool based on real-time needs and network parameters. This approach minimizes interference and optimizes bandwidth usage.  

### **Advantages**  
- Optimized utilization of network resources.  
- Reduced chances of service denial and call blocking for voice transmissions.  
- Suitable for bursty traffic, as bandwidth allocation adjusts to traffic volume.  

### **Disadvantages**  
- Increased computational and storage load on the system.  

---

## **Conclusion**  
Efficient channel allocation is vital for optimizing bandwidth usage and maintaining network performance. While **Fixed Channel Allocation** is suitable for simpler, static environments, **Dynamic Channel Allocation** provides the flexibility needed for dynamic, high-traffic scenarios.

---
# **View Docs for more Mathematical Model of Channel Allocation Problem**

### **Steps**  

1. **Begin**  
2. **For** \( i = p+1 \) to \( n \):  
   1. **For** \( c = 1 \) to \( k \):  
      - Set \( \text{allocated}_i = c \).  
      - Define \( D = \{ j : \text{allocated}_j = c \} \).  
      - Compute \( \text{sum}_d = \sum_{x \in D} I_{d,x} \) for each \( d \in D \).  
      - **If** \( \text{sum}_d > \tau_d' \):  
        - Set \( \text{allocated}_i = 0 \).  
        - **Break** inner loop.  
      - **End If**  
   2. **If** \( \text{allocated}_i = c \):  
      - **Break** outer loop.  
   3. **End If**  
3. **End For**  
4. **Stop**
