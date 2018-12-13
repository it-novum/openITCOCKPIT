import sys
from whitenoise.django import DjangoWhiteNoise

sys.path.append('/opt/graphite/webapp')
from graphite.wsgi import application as graphapp
application = DjangoWhiteNoise(graphapp)


