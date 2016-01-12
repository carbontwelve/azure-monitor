# Azure Monitor

This is a simple command line tool that I am writing to assist in viewing the resource use for selected Azure VMs and to
action upon certain alarms.

As this is a small personal project I do not expect any one else to use it, however if you do find it helpful feel free
to throw me a tweet over at [@carbontwelve](https://twitter.com/carbontwelve) or visit my blog at [photogabble](http://photogabble.co.uk).

## Todo list

 - [x] Build basic framework and unit tests
 - [ ] Build initial command that saves config to a local YAML file
 - [ ] Build azure command that loads config from local YAML file and displays list of VMs
    - [ ] Using Windows Azure PHP SDK, connect to the [diagnostics store](http://blogs.msdn.com/b/silverlining/archive/2011/09/19/how-to-get-diagnostics-info-for-azure-php-applications-part-1.aspx) for retreval of CPU/Memory/etc usage
    - [ ] Build in alerts based upon diagnostics, which once fired are able to execute commands
