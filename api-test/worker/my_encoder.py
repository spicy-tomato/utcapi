import datetime
from json import JSONEncoder


class MyEncoder(JSONEncoder):
    def default(self, object):
        if isinstance(object, datetime.date):
            return str(object.day) + "/" + str(object.month) + "/" + str(object.year)

        return object.__dict__
