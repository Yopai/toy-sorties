import React from "react";
import Moment from 'react-moment';
import moment from 'moment';

moment.locale('fr', {
    months                : 'janvier_février_mars_avril_mai_juin_juillet_août_septembre_octobre_novembre_décembre'.split('_'),
    monthsShort           : 'janv._févr._mars_avr._mai_juin_juil._août_sept._oct._nov._déc.'.split('_'),
    monthsParseExact      : true,
    weekdays              : 'dimanche_lundi_mardi_mercredi_jeudi_vendredi_samedi'.split('_'),
    weekdaysShort         : 'dim._lun._mar._mer._jeu._ven._sam.'.split('_'),
    weekdaysMin           : 'Di_Lu_Ma_Me_Je_Ve_Sa'.split('_'),
    weekdaysParseExact    : true,
    longDateFormat        : {
        LT  : 'HH:mm',
        LTS : 'HH:mm:ss',
        L   : 'DD/MM/YYYY',
        LL  : 'D MMMM YYYY',
        LLL : 'D MMMM YYYY HH:mm',
        LLLL: 'dddd D MMMM YYYY HH:mm'
    },
    calendar              : {
        sameDay : '[Aujourd’hui à] LT',
        nextDay : '[Demain à] LT',
        nextWeek: 'dddd [à] LT',
        lastDay : '[Hier à] LT',
        lastWeek: 'dddd [dernier à] LT',
        sameElse: 'L'
    },
    relativeTime          : {
        future: 'dans %s',
        past  : 'il y a %s',
        s     : 'quelques secondes',
        m     : 'une minute',
        mm    : '%d minutes',
        h     : 'une heure',
        hh    : '%d heures',
        d     : 'un jour',
        dd    : '%d jours',
        M     : 'un mois',
        MM    : '%d mois',
        y     : 'un an',
        yy    : '%d ans'
    },
    dayOfMonthOrdinalParse: /\d{1,2}(er|e)/,
    ordinal               : function (number) {
        return number + (number === 1 ? 'er' : 'e');
    },
    meridiemParse         : /AM|PM/,
    isPM                  : function (input) {
        return input.charAt(0) === 'P';
    },
    // In case the meridiem units are not separated around 12, then implement
    // this function (look at locale/id.js for an example).
    // meridiemHour : function (hour, meridiem) {
    //     return /* 0-23 hour, given meridiem token and hour 1-12 */ ;
    // },
    meridiem              : function (hours, minutes, isLower) {
        return hours < 12 ? 'AM' : 'PM';
    },
    week                  : {
        dow: 1, // Monday is the first day of the week.
        doy: 4  // Used to determine first week of the year.
    }
});

class OutingsList extends React.Component {
    constructor() {
        super();

        this.state = {
            groupMode: 'day',
            outings  : null,
            filter   : null,
            locale   : 'fr-FR',
            error    : null,
        };
    }

    componentDidMount() {
        fetch('/api/outings?sources=' + this.props.sources)
            .then((response) => {
                if (!response.ok) {
                    throw Error(response.statusText);
                }
                return response.json();
            })
            .then(outings => {
                this.setState({outings});
            })
            .catch((error) => {
                this.setState({error});
            });
    }

    filter(outings, filter) {
        return outings;
    }

    group(outings, groupMode) {
        let temp = [];
        outings.map((outing) => {
            let name;
            let key;
            switch (groupMode) {
                case 'day':
                    key  = outing.startDate;
                    name = moment(key).format('dddd DD/MM/YYYY');
                    break;
            }
            if (!temp[key]) {
                temp [key] = {key: key, name: name, outings: []};
            }
            temp[key].outings.push(outing);
        });
        let result = [];
        Object.keys(temp).sort().forEach(function (key) {
            result[result.length] = temp[key];
        });
        return result;
    }

    render() {
        if (this.state.error) {
            return this.state.error;
        }

        // let groups = this.group(this.filter(this.state.outings, this.state.filter), this.state.groupMode);
        if (this.state.outings === null) {
            return (<p>Chargement...</p>);
        }

        let groups = this.group(this.state.outings, this.state.groupMode);
        return (
            <div className="outings-list">
                <div>{this.state.outings.length} sorties trouvées</div>
                {groups.map(
                    (group) => (
                        <div className="card mb-3" key={group.name}>
                            <a className="card-header" data-toggle="collapse" href={'#group-' + group.key}>
                                {group.name}
                                <span className="badge badge-pill badge-info pull-right">{group.outings.length}</span>
                            </a>
                            <div className="list-group list-group-flush collapse show" id={'group-' + group.key}>
                                {group.outings.map(
                                    (outing) => {
                                        let is_full = outing.full
                                        return <a href={outing.externalUrl} target="_blank"
                                                  className={"outing list-group-item list-group-item-action" + (is_full ? ' full' : '')}
                                                  key={outing.id + outing.title}>
                                            <div className="media">
                                                <i className={'mr-3 fa fa-fw fa-' + (outing.category ? outing.category.icon : 'question')}/>
                                                <div className="detail d-flex justify-content-between align-items-center">
                                                    <div className="left">
                                                        <Moment date={outing.startTime} format="HH:mm"/>
                                                        <div className={"badge " + (is_full ? 'badge-danger' : '') + " badge-pill"}>
                                                            {outing.currentRegistrations} / {outing.maxRegistrations}
                                                        </div>
                                                    </div>
                                                    <div className="main">
                                                        <h5>{outing.title}</h5>
                                                        <div>
                                                            <span className="source">{outing.source.name}</span> − <span
                                                            className="author">{outing.author}</span>
                                                        </div>
                                                    </div>
                                                    <div className="actions btn-group">
                                                        <button type="button" className="btn btn-outline-dark">
                                                            <i className="fa fa-minus" title="Non, pas ce genre de sortie"/>
                                                        </button>
                                                        <button type="button" className="btn btn-outline-dark">
                                                            <i className="fa fa-question" title="Je réfléchis"/>
                                                        </button>
                                                        <button type="button" className="btn btn-outline-dark">
                                                            <i className="fa fa-heart-broken" title="J'adorerais, mais je ne peux pas"/>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>;
                                    }
                                )}
                            </div>
                        </div>
                    ))
                }
            </div>
        )
    }
}

export default OutingsList;
