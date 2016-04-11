#!/usr/bin/env bash

export PATH=$PATH:/usr/bin
export NODE_PATH=$NODE_PATH:/usr/lib/node_modules
APPLICATION_PATH="$(dirname `readlink -f $0 || realpath $0`)"
NODE="$(which nodejs)"
FOREVER="$(which forever)"
CONVERTER="$(which convert)"
MIN_UPTIME=5000
SPIN_SLEEP_TIM=2000
USER="www-data"
GROUP="www-data"

if [ -z "$NODE" ]; then
    echo "Please run: apt-get install nodejs npm && ln -s \"$(which nodejs)\" /usr/bin/node"
    exit 0
fi

if [ -z "$FOREVER" ]; then
    echo "Please run: npm -g install forever forever-monitor"
    exit 0
fi

if [ -z "$CONVERTER" ]; then
    echo "Please run: apt-get install imagemagick"
    exit 0
fi

APPLICATIONS=(
  "photos.js"
  "delete_photos.js"
  #"api.js"
  #"registrator.js"
)

umask 002

if [ -f /etc/default/forever ]; then
    . /etc/default/forever
fi

if [ ! -d /var/run/forever ]; then
    mkdir /var/run/forever
    chown $USER:$GROUP /var/run/forever
fi

if [ ! -d /var/log/forever ]; then
    mkdir /var/log/forever
    chown $USER:$GROUP /var/log/forever
fi

start() {
    for NAME in "${APPLICATIONS[@]}"; do

      echo "Starting: $NAME"

      $FOREVER start \
        --pidFile /var/run/forever/$NAME.pid \
        --minUptime $MIN_UPTIME \
        --spinSleepTime $SPIN_SLEEP_TIME \
        -l /var/log/forever/$NAME.log \
        -e /var/log/forever/$NAME.err.log -a \
        $APPLICATION_PATH/$NAME > /dev/null 2>&1 &

      echo `$FOREVER list` | grep -q "$APPLICATION_PATH/$NAME"
      if [ "$?" -eq "0" ]; then
          echo "$NAME is running."
      else
          echo "$NAME is not running."
      fi

    done
    RETVAL=$?
}

stop() {
    for NAME in "${APPLICATIONS[@]}"; do
      if [ -f /var/run/forever/$NAME.pid ]; then

          echo "Shutting down $NAME"

          $FOREVER stop \
            --pidFile /var/run/forever/$NAME.pid \
            $APPLICATION_PATH/$NAME > /dev/null 2>&1

          if [ $? -ne 0 ]; then
            rm -f /var/run/forever/$NAME.pid
          fi

          RETVAL=$?
      else
          echo "$NAME is not running."
          RETVAL=0
      fi
    done
}

status() {
    for NAME in "${APPLICATIONS[@]}"; do
      echo `$FOREVER list` | grep -q "$APPLICATION_PATH/$NAME"
      if [ "$?" -eq "0" ]; then
          echo "$NAME is running."
          RETVAL=0
      else
          echo "$NAME is not running."
          RETVAL=3
      fi
    done
}

case "$1" in
    start)
        start
        ;;
    stop)
        stop
        ;;
    status)
        status
        ;;
    restart)
        restart
        ;;
    *)
        echo "Usage: {start|stop|status|restart}"
        exit 1
        ;;
esac
exit $RETVAL