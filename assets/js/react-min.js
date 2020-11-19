'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Board = function (_React$Component) {
    _inherits(Board, _React$Component);

    function Board() {
        _classCallCheck(this, Board);

        return _possibleConstructorReturn(this, (Board.__proto__ || Object.getPrototypeOf(Board)).apply(this, arguments));
    }

    _createClass(Board, [{
        key: 'render',
        value: function render() {
            var _this2 = this;

            var className = 'board px-4';
            if (this.props.selected) {
                className += ' selected';
            }
            return React.createElement(
                'div',
                { className: className, onClick: function onClick(e) {
                        _this2.props.handleClick(e, _this2.props.index);
                    } },
                this.props.index + 1
            );
        }
    }]);

    return Board;
}(React.Component);

var BoardSwitcher = function (_React$Component2) {
    _inherits(BoardSwitcher, _React$Component2);

    function BoardSwitcher() {
        _classCallCheck(this, BoardSwitcher);

        var _this3 = _possibleConstructorReturn(this, (BoardSwitcher.__proto__ || Object.getPrototypeOf(BoardSwitcher)).call(this));

        _this3.state = {
            clickedIndex: 4
        };
        return _this3;
    }

    _createClass(BoardSwitcher, [{
        key: 'onButton',
        value: function onButton(e, index) {
            e.preventDefault();
            this.setState({
                clickedIndex: index
            });
        }
    }, {
        key: 'render',
        value: function render() {
            var boards = [];

            for (var ii = 0; ii < this.props.numBoards; ii++) {
                var isSelected = ii == this.state.clickedIndex;
                boards.push(React.createElement(Board, { index: ii, key: ii, selected: isSelected, handleClick: this.onButton.bind(this) }));
            }

            return React.createElement(
                'div',
                { className: 'boards d-flex justify-content-between mx-auto' },
                boards
            );
        }
    }]);

    return BoardSwitcher;
}(React.Component);

var domContainer = document.querySelector('#react-dom');
ReactDOM.render(React.createElement(BoardSwitcher, { numBoards: 5 }), domContainer);
