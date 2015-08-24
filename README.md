# StatusEngine2Cachet

StatusEngine2Cachet is a link between [StatusEngine](https://github.com/R11T/StatusEngine) and [Cachet](https://github.com/cachethq/Cachet). It helps to give a meaning to the results gathered by the probes and automatically update Cachet via its API.

It's far far away to be usable in a production environment, every good soul are welcome to contribute (TODO is here to show direction I have in mind).

## TODO
Following the MoSCoW method :
* The system *must* be a surjective application from probes to components
* The system *must* modify a cachet component following statuses of probes
* The system *should* evaluate the availability of a component according to probes organization (response delay, internals relations among probes, …)
* The system *could* add a component
* The system *would* add as a metric a general availability percentage
* The system *would* post a incident report template


## Disclaimer

StatusEngine2Cachet is *not* affiliated in any way to CachetHQ or Cachet ; i just give, modestly, a little bit of automation to this fantastic tool.

## License

Status Engine is under GPL-v2 license, see LICENSE file for details.
