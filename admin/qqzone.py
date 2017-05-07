# -*- coding: UTF-8 -*-
import httplib2
import sys

qqnum = str(sys.argv[1]);
p_skey =str(sys.argv[2]);

#qqnum = '211342495';
#p_skey='-H09Hg3p9iuaYM4ACkdzFTNOnoBGZotRnJ721LoiCt0_';
# python ./qqzone.py 2014336837 d3RJHm0WNXl40bYeYCuVTf42-cS5OKzbJ8AA-sMJ-z0_

def LongToInt(value):  # 由于int+int超出范围后自动转为long型，通过这个转回来
    if isinstance(value, int):
        return int(value)
    else:
        return int(value & 0x7fffffff)
def LeftShiftInt(number, step):  # 由于左移可能自动转为long型，通过这个转回来
    if isinstance((number << step), long):
        return int((number << step) - 0x200000000L)
    else:
        return int(number << step)
def getNewGTK(p_skey):
    a = 5381
    for i in range(0, len(p_skey)):
        a = a + LeftShiftInt(a, 5) + ord(p_skey[i])
        a = LongToInt(a)
    return a & 0x7fffffff

h = httplib2.Http()

g_tk =getNewGTK(p_skey);
url = 'https://h5.qzone.qq.com/proxy/domain/r.qzone.qq.com/cgi-bin/tfriend/friend_show_qqfriends.cgi?uin='+qqnum+'&follow_flag=1&groupface_flag=0&fupdate=1&g_tk='+str(g_tk);
#url = 'https://h5.qzone.qq.com/proxy/domain/r.qzone.qq.com/cgi-bin/tfriend/friend_show_qqfriends.cgi?uin=211342495&follow_flag=1&groupface_flag=0&fupdate=1&g_tk=1239417276'
#url ='https://h5.qzone.qq.com/proxy/domain/r.qzone.qq.com/cgi-bin/tfriend/friend_ship_manager.cgi?uin='+qqnum+'&do=1&rd=0.5368571688404303&fupdate=1&clean=1&g_tk='+str(g_tk) ;
cookieStr = 'uin=o'+qqnum+';  p_uin=o'+qqnum+'; p_skey='+p_skey;
headers = {'Cookie': cookieStr}
resp, content = h.request(url, 'GET', headers=headers)
print content
