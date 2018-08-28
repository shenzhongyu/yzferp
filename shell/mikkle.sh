#!/bin/bash
# chkconfig: 2345 10 90
# description: Start and Stop Mikkle
# power by Mikkle 
# QQ 776329498
PHP="/www/wdlinux/php/bin/php"

MIKKLE_DIV="/www/web/mikkle"
MIKKLE_SCRIPT='think' #服务脚本
MIKKLE_NAME="mikkle"  #你自定义的服务名称
MIKKLE_PID="${MIKKLE_DIV}/shell/service/${MIKKLE_NAME}.pid" #服务PID
MIKKLE_LOG="${MIKKLE_DIV}/shell/log/${MIKKLE_NAME}.log" #服务运行日志 记录错误信息

#判断程序是否已经在运行
status_script(){
    ps -fe|grep ${MIKKLE_SCRIPT}|grep ${MIKKLE_NAME}|grep -v grep
    if [ $? -eq 0 ]
    then
        echo ${0}' Is running'
        running=1

    elif [ $? -ne 0 ]
    then
        echo $0" is NOT running"
        running=2
    fi
}

#启动脚本，先判断脚本是否已经在运行
start_script(){
status_script
    if [ ${running} -eq 1 ]
    then
        echo ${0}' Is starting ...'
    else
        echo 'start' ${0} '...'
        cd ${MIKKLE_DIV}
        nohup ${PHP} ${MIKKLE_SCRIPT} ${MIKKLE_NAME}>/dev/null 2>${MIKKLE_LOG} &
        echo $! > ${MIKKLE_PID}
        echo "start finish,PID $!"
    fi
}

#停止脚本
stop_script(){

status_script
    if [ ${running} -ne 1 ]
    then
        echo ${0}' no starting '$?...
    else
    PIDS=`ps aux|grep ${MIKKLE_SCRIPT}|grep ${MIKKLE_NAME}| grep -v grep |awk '{print $2}'`
       for kill_pid in ${PIDS}
       do
            kill -TERM ${kill_pid} >/dev/null 2>&1
            echo "Kill pid ${kill_pid} .."
        done
        echo 'stop complete'
        return 1
    fi

}

#重启脚本
reload_script(){
    stop_script
    sleep 3
    start_script
}
#入口函数
handle(){
    case $1 in
    start)
        start_script
        ;;
    stop)
        stop_script
        ;;
    status)
        status_script
        ;;
    reload)
        reload_script
        ;;
    restart)
        reload_script
        ;;
    *)
        echo 'MIKKLE OF THIS SERVER IS '${0} 'status|start|stop|restart';
        ;;
    esac
}

if [ $# -eq 1 ]
then
    handle $1
else
    echo 'Mikkle OF THIS SERVER IS '${0} 'status|start|stop|restart';
fi
