我在自己的项目中很少使用transient关键字。这个关键字的作用是和序列化相关的。JDK的源码中ArrayList类用到了transient关键字.整理一下这个关键字相关的内容.


```
transient Object[] elementData; // non-private to simplify nested class access
```
