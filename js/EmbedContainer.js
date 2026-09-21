"use strict";
(self.webpackChunk = self.webpackChunk || []).push([
    [870], {
        100: function(e, t, s) {
            s.r(t);
            s.d(t, {
                default: function() {
                    return ce
                }
            });
            var i = {};
            s.r(i);
            s.d(i, {
                PRIMARY: function() {
                    return x
                },
                SECONDARY: function() {
                    return N
                },
                TRANSPARENT_ON_PRIMARY: function() {
                    return U
                }
            });
            var a = s(21),
                r = s(57),
                o = s(61),
                n = s.n(o),
                d = s(9),
                l = s(1),
                p = s(8),
                h = s(99),
                c = s(15),
                u = s(13),
                g = s.n(u),
                y = s(4),
                m = s(88),
                v = s(90),
                f = s(10);
            const b = 1,
                P = 10,
                S = 3600,
                k = e => ({
                    videoDuration: e.duration / 1e3,
                    videoId: e.videoId,
                    videoTitle: e.title
                });

            function T(e) {
                const t = {};
                e.forEach(((e, s) => {
                    t[s] = e
                }));
                return t
            }

            function C(e) {
                try {
                    return e.responseText
                } catch (e) {
                    return ""
                }
            }
            class E {
                constructor(e, {
                    embedId: t,
                    sessionId: s,
                    utk: i,
                    pageMeta: a,
                    postMessageToParent: r
                }, o, n) {
                    this.trackedChunksLoaded = !1;
                    this.trackedCompletion = !1;
                    this.chunksLoaded = 0;
                    this.totalChunksLoaded = 0;
                    this.bytesLoaded = 0;
                    this.totalBytesLoaded = 0;
                    this.durationLoaded = 0;
                    this.totalDurationLoaded = 0;
                    this.trackCompletedPlay = () => {
                        if (!this.utk || this.trackedCompletion) return;
                        const e = Object.assign({}, this.pageMeta, {
                            secondsViewed: this.getViewDuration(),
                            percentageViewed: this.getPercentageViewed()
                        });
                        (0, m.trackCompletedPlay)(this.video.crmObjectId, this.sessionId, this.utk, e)
                    };
                    this.video = e;
                    this.embedId = t;
                    this.sessionId = s;
                    this.utk = i;
                    this.pageMeta = a;
                    this.chunksViewed = new Map;
                    this.chunksViewedSinceReport = new Map;
                    this.trackUsage = o;
                    this.player = n;
                    this.trackingStartedAt = (new Date).getTime();
                    this.postMessageToParent = r || (() => null);
                    n && n.ready((() => {
                        this.setupHlsChunkTracking()
                    }))
                }
                trackPlay() {
                    const {
                        sessionId: e,
                        utk: t,
                        pageMeta: s,
                        video: i
                    } = this;
                    if (!t || !s || this.playEventPromise) return this.playEventPromise || Promise.resolve();
                    const a = Object.assign({}, s, {
                        crmObjectId: i.crmObjectId,
                        sessionId: e,
                        utk: t
                    });
                    this.playEventPromise = (0, m.trackPlayEvent)(i.crmObjectId, e, t, s).then((() => {
                        this.trackUsage("playerInteraction", {
                            action: "tracked-play"
                        });
                        this.postMessageToParent(y.MessageTypes.TRACKED_PLAY, a)
                    })).catch((e => {
                        const t = {
                            status: e.status,
                            responseText: C(e),
                            requestBody: a
                        };
                        g().captureMessage("Failed to track play event", {
                            extra: t,
                            level: "warning",
                            tags: {
                                status: e.status
                            }
                        });
                        f.Metrics.counter("play-tracking-request-failed", {
                            status: e.status
                        }).increment()
                    }));
                    return this.playEventPromise
                }
                trackNewChunksViewed(e = !1) {
                    if (!this.utk || !this.pageMeta || !this.chunksViewedSinceReport.size) return;
                    const t = T(this.chunksViewedSinceReport),
                        s = Object.assign({}, this.pageMeta, {
                            secondsToViews: t,
                            endState: e
                        });
                    (0, p.debugLog)(`Tracking ${e?"final":"interval"} seconds viewed`, t);
                    (0, m.trackSecondsViewed)(this.video.crmObjectId, this.sessionId, this.utk, s).catch((e => {
                        f.Metrics.counter("retention-tracking-request-failed", {
                            status: e.status
                        }).increment()
                    }));
                    this.postMessageToParent(y.MessageTypes.PLAYER_SECONDS_VIEWED, {
                        secondsViewed: this.chunksViewed.size
                    });
                    this.chunksViewedSinceReport.clear();
                    this.lastRetentionReported = Date.now()
                }
                fillRemainingSecondsViewed() {
                    const e = this.video.duration / 1e3,
                        t = Math.ceil(e / b);
                    if (this.chunksViewed.size < t) {
                        (0, p.debugLog)(t - this.chunksViewed.size + " remaining chunks to track for looped video");
                        for (let e = 0; e <= t; e++) this.chunksViewed.get(e) || this.chunksViewedSinceReport.get(e) || this.chunksViewedSinceReport.set(e, 1)
                    }
                }
                onChunkViewed(e) {
                    if (this.chunksViewed.get(e)) return;
                    this.chunksViewed.set(e, (this.chunksViewed.get(e) || 0) + 1);
                    this.chunksViewedSinceReport.set(e, (this.chunksViewedSinceReport.get(e) || 0) + 1);
                    this.lastRetentionReported || (this.lastRetentionReported = Date.now());
                    if ((Date.now() - this.lastRetentionReported) / 1e3 >= P) {
                        this.trackNewChunksViewed();
                        this.trackHlsChunksLoaded()
                    }
                }
                getViewDuration() {
                    return this.chunksViewed.size * b
                }
                getPercentageViewed() {
                    return this.video.duration ? Math.round(this.getViewDuration() / (this.video.duration / 1e3) * 100) : null
                }
                trackAttentionSpan(e = !1) {
                    if (this.trackedCompletion) return;
                    const {
                        embedId: t,
                        video: s,
                        totalChunksLoaded: i,
                        totalBytesLoaded: a,
                        totalDurationLoaded: r
                    } = this, o = Object.assign({}, k(s), {
                        completed: e,
                        viewDuration: this.getViewDuration(),
                        chunksLoaded: i,
                        megaBytesLoaded: a / 1e3 / 1e3,
                        secondsLoaded: r
                    });
                    this.trackUsage("videoAttentionSpan", o);
                    (0, p.debugLog)(`Tracking final attention span for player: ${t}`, o);
                    this.trackCompletedPlay();
                    this.trackedCompletion = !0
                }
                setupHlsChunkTracking() {
                    const e = (0, v.getHlsTech)(this.player);
                    e && e.on("hlsFragLoaded", ((e, t) => {
                        this.chunksLoaded += 1;
                        this.totalChunksLoaded += 1;
                        if (t.frag && t.frag.stats && t.frag.stats.total) {
                            this.durationLoaded += t.frag.duration;
                            this.totalDurationLoaded += t.frag.duration;
                            this.bytesLoaded += t.frag.stats.total;
                            this.totalBytesLoaded += t.frag.stats.total
                        }
                    }))
                }
                trackHlsChunksLoaded() {
                    if (!this.chunksLoaded) return;
                    const {
                        embedId: e,
                        video: t,
                        chunksLoaded: s,
                        bytesLoaded: i,
                        durationLoaded: a,
                        totalChunksLoaded: r,
                        totalBytesLoaded: o,
                        totalDurationLoaded: n
                    } = this, d = Object.assign({}, k(t), {
                        initial: !this.trackedChunksLoaded,
                        viewDuration: this.getViewDuration(),
                        chunksLoaded: s,
                        megaBytesLoaded: i / 1e3 / 1e3,
                        secondsLoaded: a
                    });
                    this.trackUsage("hlsChunksLoaded", d);
                    (0, p.debugLog)(`Tracking HLS chunks loaded for player: ${e}`, d);
                    (0, p.debugLog)(`HLS chunk totals for player ${e}`, {
                        totalChunksLoaded: r,
                        totalBytesLoaded: o,
                        totalDurationLoaded: n
                    });
                    this.chunksLoaded = 0;
                    this.bytesLoaded = 0;
                    this.durationLoaded = 0;
                    this.trackedChunksLoaded = !0
                }
                getSessionDuration() {
                    return (Date.now() - this.trackingStartedAt) / 1e3
                }
                hasViewedCompletely() {
                    const e = this.video.duration / 1e3,
                        t = Math.floor(e / b);
                    return this.chunksViewed.size >= t
                }
                getTimeRangesDuration(e) {
                    const t = [];
                    let s = 0;
                    for (; s < e.length;) {
                        t.push(e.end(s) - e.start(s));
                        s++
                    }
                    return t.sort()[t.length - 1]
                }
            }

            function R(e, t, {
                embedId: s,
                sessionId: i,
                utk: a,
                pageMeta: r,
                postMessageToParent: o
            }, n) {
                const d = e.duration / 1e3,
                    l = new E(e, {
                        embedId: s,
                        sessionId: i,
                        utk: a,
                        pageMeta: r,
                        postMessageToParent: o
                    }, n, t);
                (0, p.debugLog)(a ? "Setting up play & retention tracking for player" : "No utk for play / retention tracking", r, l);
                const h = () => {
                        if (l.trackedCompletion) return;
                        const e = Math.floor(t.currentTime() / b);
                        l.onChunkViewed(e);
                        if (l.getSessionDuration() >= S) {
                            (0, p.debugLog)(`Ending retention tracking due to session duration reaching ${l.getSessionDuration()}`);
                            l.trackNewChunksViewed(!1);
                            l.trackAttentionSpan(!1);
                            t.loop() && t.loop(!1)
                        }
                        if (t.loop()) {
                            const e = l.getTimeRangesDuration(t.played());
                            if (e >= d) {
                                (0, p.debugLog)(`Ending retention tracking - TimeRanges with duration ${e} indicate complete view`);
                                l.fillRemainingSecondsViewed();
                                l.trackNewChunksViewed(!0);
                                l.trackAttentionSpan(!0)
                            }
                            if (l.hasViewedCompletely()) {
                                (0, p.debugLog)("Ending retention tracking for looped video watched completely", d, l.getSessionDuration());
                                l.trackNewChunksViewed(!0);
                                l.trackAttentionSpan(!0)
                            }
                        }
                    },
                    c = e => {
                        (0, p.debugLog)("Ended retention tracking due to ended event", e);
                        l.trackNewChunksViewed(!0);
                        l.trackAttentionSpan(l.hasViewedCompletely())
                    },
                    u = () => {
                        if (!l.trackedCompletion && "hidden" === document.visibilityState) {
                            l.trackHlsChunksLoaded();
                            if (t.hasStarted()) {
                                (0, p.debugLog)(`Ending retention tracking for player ${s} due to visibilityState change to: ${document.visibilityState}`);
                                l.trackNewChunksViewed(!0);
                                l.trackAttentionSpan(!1);
                                document.removeEventListener("visibilitychange", u)
                            }
                            if (l.getSessionDuration() >= S) {
                                (0, p.debugLog)("Detaching visibilitychange listener after 1 hour");
                                document.removeEventListener("visibilitychange", u)
                            }
                        }
                    };
                t.on("playing", (() => {
                    l.trackPlay().catch((e => {
                        throw e
                    }))
                }));
                t.on("timeupdate", h);
                t.on("ended", c);
                document.addEventListener("visibilitychange", u);
                return l
            }
            var L = s(42),
                A = s(18),
                w = s(98),
                I = s(80),
                O = s(72),
                M = s(17),
                V = s(50),
                _ = s(82),
                D = s(73),
                j = s(74);
            const x = "primary",
                N = "secondary",
                U = "transparent-on-primary";
            var F = s(79),
                Y = s(75),
                B = s(77),
                H = s(83);
            const q = {
                baseStyle: (0, V.css)(["font-family:inherit;padding:", ";font-size:", ";flex-shrink:0;border-radius:3px;line-height:16px;outline:none;transition:background-color 150ms ease-out;border-style:solid;border-width:1px;cursor:pointer;text-align:center;word-break:normal;overflow-wrap:break-word;background-color:transparent;", ""], (({
                    size: e
                }) => H.BUTTON_PADDINGS[e]), (({
                    size: e
                }) => H.BUTTON_FONT_SIZES[e]), (({
                    theme: {
                        colors: e
                    },
                    use: t
                }) => "primary" === t ? `\n          background-color: ${e.primary};\n          border: none;\n          color: ${e.textOnPrimary};\n        ` : `\n          background-color: transparent;\n          border-color: ${e.primary};\n          color: ${e.primary};\n        `)),
                _disabled: (0, V.css)(["background-color:", ";border:1px solid ", ";color:", ";cursor:not-allowed;user-select:none;"], (({
                    theme: {
                        colors: e
                    }
                }) => e.disabledButtonBackground), (({
                    theme: {
                        colors: e
                    }
                }) => e.disabledButtonBorder), (({
                    theme: {
                        colors: e
                    }
                }) => e.disabledButtonText)),
                _focused: Y.focusRing,
                _hovered: (0, V.css)(["", ""], (({
                    theme: {
                        colors: e
                    },
                    use: t
                }) => `background-color: ${(0,B.adjustLuminance)(e.primary,"primary"===t?20:95)};`)),
                _pressed: (0, V.css)(["", ""], (({
                    theme: {
                        colors: e
                    },
                    use: t
                }) => `background-color: ${(0,B.adjustLuminance)(e.primary,"primary"===t?-10:90)};`))
            };
            var $ = s(55);
            const z = ["use", "size", "children"],
                W = V.default.button.withConfig({
                    displayName: "VizExButton__AbstractVizExButton",
                    componentId: "tvxzym-0"
                })(["", ""], (({
                    theme: e
                }) => (0, F.default)({
                    component: "Button",
                    defaultStyles: q,
                    theme: e
                }))),
                J = V.default.div.withConfig({
                    displayName: "VizExButton__NoSelect",
                    componentId: "tvxzym-1"
                })(["user-select:none;"]),
                G = (0, a.forwardRef)(((e, t) => {
                    let {
                        use: s = N,
                        size: i = _.MEDIUM,
                        children: a
                    } = e, r = (0, M.default)(e, z);
                    return (0, $.jsx)(W, Object.assign({}, Object.assign({
                        use: s,
                        size: i
                    }, r), {
                        ref: t,
                        children: (0, $.jsx)(J, {
                            children: a
                        })
                    }))
                }));
            G.displayName = "VizExButton";
            G.propTypes = Object.assign({
                children: n().node,
                onClick: n().func,
                size: n().oneOf([_.EXTRA_SMALL, _.SMALL, _.MEDIUM]),
                theme: D.default,
                use: n().oneOf(Object.values(i))
            }, j.interactionPropTypes);
            var X = G,
                K = s(81);
            class Z extends a.PureComponent {
                constructor(...e) {
                    super(...e);
                    this.formWrapperRef = null;
                    this.state = {
                        showingInlineMessage: !1
                    };
                    this.loadForm = () => {
                        const {
                            conversionAsset: e,
                            video: t,
                            playButtonColor: s,
                            onSkip: i
                        } = this.props;
                        if (null === this.formWrapperRef) throw new Error("Error loading form: formWrapperRef is null");
                        if (!t) throw new Error("Error loading form: no video found");
                        (0, w.renderPlayerPreviewForm)(this.formWrapperRef, e, t, {
                            playButtonColor: s
                        }, {
                            onSubmit: this.handleFormSubmitted,
                            onSkip: i
                        }).then((e => {
                            this.form = e
                        })).catch((e => {
                            console.error("Error rendering preview form", e);
                            g().captureException(e)
                        }))
                    };
                    this.handleFormSubmitted = () => {
                        this.setState({
                            showingInlineMessage: !0
                        })
                    }
                }
                componentDidUpdate(e) {
                    this.props.status !== e.status && this.props.status === I.PlayerStatus.CONVERSION_ASSET_OVERLAY && this.loadForm()
                }
                render() {
                    const {
                        conversionAsset: e,
                        status: t,
                        onSkip: s,
                        onPostSubmit: i
                    } = this.props, {
                        showingInlineMessage: a
                    } = this.state, r = !e.preventSkip;
                    return t !== I.PlayerStatus.CONVERSION_ASSET_OVERLAY ? null : (0, $.jsxs)(K.default, {
                        className: "hsv-form-modal",
                        allowClose: r,
                        onClose: s,
                        children: [(0, $.jsx)("div", {
                            className: w.MODAL_FORM_WRAPPER_CLASSNAME,
                            ref: e => {
                                this.formWrapperRef = e
                            }
                        }), a && (0, $.jsx)("div", {
                            style: {
                                display: "flex",
                                margin: "20px 0",
                                justifyContent: "center"
                            },
                            children: (0, $.jsx)(X, {
                                onClick: i,
                                use: "primary",
                                "data-test-id": "form-watch-video",
                                children: (0, $.jsx)(O.default, {
                                    message: "videoPlayer.formModal.inlineMessageButton"
                                })
                            })
                        })]
                    })
                }
            }
            Z.propTypes = {
                status: n().oneOf(Object.values(I.PlayerStatus)),
                id: n().string.isRequired,
                conversionAsset: n().object.isRequired,
                video: n().object.isRequired,
                playButtonColor: n().string,
                onSkip: n().func.isRequired,
                onPostSubmit: n().func.isRequired
            };
            var Q = s(71),
                ee = s(60),
                te = s(44),
                se = s(2);

            function ie(e, t) {
                const s = e.bigPlayButton.el(),
                    i = t.playButtonShape ? t.playButtonShape.toUpperCase() : I.PlayButtonShapes.CIRCLE;
                Object.values(I.PlayButtonShapes).includes(i) && s.classList.add(`play-button-${i.toLowerCase()}`);
                let a = t.playButtonColor || I.DEFAULT_PLAY_BUTTON_COLOR;
                a.startsWith("#") || (a = `#${a}`);
                (0, se.setStyles)(s, {
                    backgroundColor: a
                });
                s.innerHTML = I.PLAY_BUTTON_SVG
            }
            const ae = {
                embedId: n().string.isRequired,
                status: n().oneOf(Object.values(I.PlayerStatus)),
                playerType: n().oneOf(Object.values(d.PlayerTypes)),
                options: n().object.isRequired,
                video: n().object.isRequired,
                title: n().string,
                pageMeta: n().object,
                currentTime: n().number,
                hasConverted: n().bool.isRequired,
                confirmedVisible: n().bool.isRequired,
                waitForConfirmedVisible: n().bool.isRequired,
                onChangeStatus: n().func.isRequired,
                onReady: n().func.isRequired,
                onPlaying: n().func.isRequired,
                onPause: n().func.isRequired,
                onEnded: n().func.isRequired,
                postMessageToParent: n().func.isRequired,
                trackUsage: n().func.isRequired,
                captions: n().array
            };
            class re extends a.PureComponent {
                constructor(...e) {
                    super(...e);
                    this.videoNode = (0, a.createRef)();
                    this.playerRef = (0, a.createRef)();
                    this.hlsLoadStarted = !1;
                    this.setCurrentTime = async e => {
                        const t = this.playerRef.current;
                        if (!t.hasStarted()) {
                            (0, p.debugLog)("Force starting player to enable setting currentTime");
                            this.startHlsLoad("set current time");
                            await new Promise((e => setTimeout(e, 100)));
                            try {
                                await t.play();
                                t.pause()
                            } catch (e) {
                                (0, p.debugLog)("Failed to play player to enable setting currentTime", e)
                            }
                        }
                        t.currentTime(e)
                    };
                    this.handleWaiting = () => {
                        const {
                            options: e,
                            status: t
                        } = this.props;
                        this.playerRef.current && t !== I.PlayerStatus.LOADING && t !== I.PlayerStatus.CONVERSION_ASSET_OVERLAY && (e.autoplay && this.playerRef.current.hasStarted() || this.props.onChangeStatus(I.PlayerStatus.LOADING))
                    };
                    this.handleReady = () => {
                        const {
                            options: e
                        } = this.props, t = this.playerRef.current;
                        this.configureHls();
                        this.props.onChangeStatus(I.PlayerStatus.READY);
                        this.props.onReady();
                        e.autoplay && setTimeout((() => {
                            t.play().catch((e => {
                                (0, p.debugLog)("Failed to autoplay, muting and retrying", e);
                                setTimeout((() => {
                                    t.muted(!0);
                                    t.play().catch((e => (0, p.debugLog)("Failed to autoplay after muting:", e)))
                                }))
                            }))
                        }));
                        this.props.captions && this.initCaptions()
                    };
                    this.startHlsLoad = e => {
                        const t = this.playerRef.current,
                            s = (0, v.getHlsTech)(t);
                        if (s) {
                            if (!this.hlsLoadStarted) {
                                this.props.trackUsage("hlsLoadStarted", {
                                    reason: e,
                                    secondsElapsed: (0, c.getSecondsElapsed)()
                                });
                                s.startLoad(-1)
                            }
                            this.hlsLoadStarted = !0;
                            this.hlsLoadTimeout && clearTimeout(this.hlsLoadTimeout)
                        }
                    };
                    this.initCaptions = () => {
                        const {
                            captions: e,
                            trackUsage: t,
                            options: {
                                subtitleLanguage: s
                            }
                        } = this.props, i = this.playerRef.current;
                        if (i.textTracks().length) {
                            (0, p.debugLog)("Text tracks already initialized in initCaptions");
                            return
                        }
                        e.forEach((({
                            lang: e,
                            label: t,
                            url: a
                        }) => {
                            const r = {
                                kind: "captions",
                                label: t,
                                src: a,
                                srclang: e,
                                mode: s === e ? "showing" : "hidden"
                            };
                            (0, p.debugLog)("Adding text track:", r);
                            i.addRemoteTextTrack(r, !1)
                        }));
                        const a = document.querySelector("button.vjs-captions-button");
                        a && a.addEventListener("click", (e => {
                            this.insertSubtitlesPopoverHeader();
                            e.target instanceof Element && a.contains(e.target) && t("playerInteraction", {
                                action: "true" === a.getAttribute("aria-expanded") ? "open-subtitles-popover" : "close-subtitles-popover"
                            })
                        }))
                    };
                    this.insertSubtitlesPopoverHeader = () => {
                        const e = document.querySelector(".vjs-captions-button .vjs-menu-content");
                        if (e && !e.querySelector(".subtitles-header")) {
                            (0, p.debugLog)("Inserting subtitles menu node", e);
                            e.insertAdjacentHTML("afterbegin", `<label class="subtitles-header">${A.default.text("videoPlayer.controls.captions")}</label>`);
                            e.insertAdjacentHTML("afterend", '<div class="popover-triangle" />')
                        }
                    };
                    this.handlePlaying = () => {
                        const {
                            status: e,
                            hasConverted: t
                        } = this.props, s = this.playerRef.current;
                        if (e !== I.PlayerStatus.CONVERSION_ASSET_OVERLAY || t) {
                            if (e !== I.PlayerStatus.PLAYING) {
                                this.props.onChangeStatus(I.PlayerStatus.PLAYING);
                                this.props.onPlaying()
                            }
                        } else {
                            (0, p.debugLog)("Refusing to play in CONVERSION_ASSET_OVERLAY state");
                            s.pause()
                        }
                    };
                    this.handlePause = () => {
                        const {
                            status: e
                        } = this.props;
                        if (e !== I.PlayerStatus.CONVERSION_ASSET_OVERLAY) {
                            this.props.onChangeStatus(I.PlayerStatus.PAUSED);
                            this.props.onPause()
                        }
                    };
                    this.handleEnded = () => {
                        const {
                            options: e
                        } = this.props;
                        this.props.onChangeStatus(I.PlayerStatus.ENDED);
                        this.props.onEnded();
                        const t = this.playerRef.current;
                        e.conversionAsset && e.conversionAsset.position === d.ConversionAssetPositions.POST && t.isFullscreen() && t.exitFullscreen()
                    };
                    this.handleVisibilityStateChange = () => {
                        const {
                            embedId: e
                        } = this.props, t = this.playerRef.current;
                        if (t.paused() && "hidden" === document.visibilityState) {
                            (0, p.debugLog)(`Player ${e} is already paused on visibilityState change to ${document.visibilityState}`);
                            this.props.trackUsage("playerInteraction", {
                                action: "paused-for-background-tab"
                            });
                            document.removeEventListener("visibilitychange", this.handleVisibilityStateChange)
                        } else if ("hidden" === document.visibilityState) {
                            (0, p.debugLog)(`Pausing player ${e} for visibilityState change to ${document.visibilityState}`);
                            this.props.trackUsage("playerInteraction", {
                                action: "paused-for-visibility-hidden"
                            });
                            t.pause();
                            document.removeEventListener("visibilitychange", this.handleVisibilityStateChange)
                        }
                    };
                    this.handlePlayerError = () => {
                        const {
                            video: e,
                            playerType: t
                        } = this.props, s = this.playerRef.current, i = s.error(), a = {
                            errorMessage: i ? i.message : String(i),
                            seeking: s.seeking(),
                            scrubbing: s.scrubbing(),
                            bufferedPercent: s.bufferedPercent(),
                            currentTime: s.currentTime(),
                            hasStarted: s.hasStarted()
                        };
                        let r = "load";
                        s.hasStarted() && (r = a.currentTime > 0 ? "playing" : "initial play");
                        const o = e.fileUrl.split(".").at(-1),
                            n = e.primaryPlaybackId ? "video/mux" : `video/${o}`;
                        f.Metrics.counter("videojs-error", {
                            phase: r,
                            playerType: t,
                            streamType: n,
                            isEmbeddable: String(e.isEmbeddable),
                            transcodingState: e.transcodingState
                        }).increment();
                        this.props.onChangeStatus(I.PlayerStatus.ERROR, i);
                        if (i && i.hlsError) {
                            const t = i.hlsError;
                            if (t.response && 412 === t.response.code) {
                                s.src({
                                    src: e.fileUrl
                                });
                                (0, p.debugLog)("Updated videojs player src to", s.src());
                                f.Metrics.counter("mux-transcoding-pending-fallback").increment();
                                return
                            }
                        }
                    };
                    this.handleClick = e => {
                        const t = this.playerRef.current;
                        if ("VIDEO" === e.target.nodeName && !t.controls())
                            if (t.paused()) {
                                var s;
                                null === (s = t.play()) || void 0 === s || s.catch((e => {
                                    throw e
                                }))
                            } else t.pause()
                    };
                    this.handleTouchStart = e => {
                        const t = this.playerRef.current;
                        if ("VIDEO" === e.target.nodeName && t.hasStarted())
                            if (t.paused()) {
                                var s;
                                null === (s = t.play()) || void 0 === s || s.catch((e => {
                                    throw e
                                }))
                            } else t.pause()
                    };
                    this.handleProgress = () => {
                        const e = this.playerRef.current.textTracks().tracks_.find((e => "showing" === e.mode)),
                            t = e && e.language;
                        this.props.postMessageToParent(y.MessageTypes.PLAYER_PROGRESS, {
                            currentTime: this.playerRef.current.currentTime(),
                            textLanguage: t
                        })
                    };
                    this.handleRateChange = () => {
                        this.props.postMessageToParent(y.MessageTypes.SET_PLAYBACK_RATE, {
                            playbackRate: this.playerRef.current.playbackRate()
                        })
                    };
                    this.handleTimeUpdate = () => {
                        this.props.postMessageToParent(y.MessageTypes.PLAYER_TIME_UPDATE, {
                            currentTime: this.playerRef.current.currentTime()
                        })
                    }
                }
                componentDidMount() {
                    const {
                        video: e,
                        options: t,
                        pageMeta: s,
                        trackUsage: i
                    } = this.props, a = this.getPlayerOptions(), r = {
                        pageUrl: s ? s.pageUrl : t.parentOrigin,
                        renderContext: t.renderContext
                    }, o = (0, v.default)(this.videoNode.current, a, e, r);
                    this.playerRef.current = o;
                    (0, p.debugLog)("Created videojs player", t, o, r);
                    ie(o, t);
                    o.errors({
                        timeout: I.VIDEOJS_LOAD_TIMEOUT_MS
                    });
                    o.on(["aderror", "contenterror", "error"], this.handlePlayerError);
                    o.on("waiting", this.handleWaiting);
                    o.one("ready", this.handleReady);
                    o.on("playing", this.handlePlaying);
                    o.on("pause", this.handlePause);
                    o.on("ended", this.handleEnded);
                    o.on("click", this.handleClick);
                    o.on("touchstart", this.handleTouchStart);
                    o.on("progress", this.handleProgress);
                    o.on("timeupdate", this.handleTimeUpdate);
                    o.on("ratechange", this.handleRateChange);
                    o.on("texttrackchange", (e => {
                        const t = e.target.player.textTracks().tracks_.find((e => "showing" === e.mode)),
                            s = t && t.language;
                        s && i("playerInteraction", {
                            action: `text-track-change-${s}`
                        });
                        this.handleProgress()
                    }));
                    (a.autoplay || a.loop) && document.addEventListener("visibilitychange", this.handleVisibilityStateChange);
                    "number" == typeof this.props.currentTime && this.setCurrentTime(this.props.currentTime).catch(console.error)
                }
                componentWillUnmount() {
                    document.removeEventListener("visibilitychange", this.handleVisibilityStateChange)
                }
                componentDidUpdate(e) {
                    const {
                        confirmedVisible: t,
                        currentTime: s,
                        waitForConfirmedVisible: i,
                        captions: a
                    } = this.props;
                    !e.captions && a && this.initCaptions();
                    i && t && !e.confirmedVisible && this.startHlsLoad("confirmed-visible");
                    "number" == typeof s && s !== e.currentTime && this.setCurrentTime(s).catch(console.error)
                }
                getPlayer() {
                    return this.playerRef.current
                }
                getPlayerOptions() {
                    const {
                        options: e,
                        areChaptersEnabled: t,
                        playerType: s
                    } = this.props, i = (0, te.pick)(e, I.PLAYER_OPTIONS);
                    i.loop && e.conversionAsset && e.conversionAsset.position === d.ConversionAssetPositions.POST && (i.loop = !1);
                    i.showChapters = t;
                    i.enablePictureInPicture = t && s !== d.PlayerTypes.ACADEMY_EMBEDDED;
                    return i
                }
                configureHls() {
                    const {
                        options: e
                    } = this.props, t = this.playerRef.current, s = (0, v.getHlsTech)(t);
                    if (s) {
                        (0, p.debugLog)(`configureHls called with loadLevel: ${s.loadLevel}`);
                        e.autoplay ? this.startHlsLoad("autoplay") : this.configureLazy()
                    } else(0, p.debugLog)("configureHls called before tech exists")
                }
                configureLazy() {
                    const e = this.playerRef.current;
                    if ((0, v.getHlsTech)(e)) {
                        this.props.waitForConfirmedVisible ? this.props.confirmedVisible && this.startHlsLoad("initially-visible") : this.hlsLoadTimeout = setTimeout((() => {
                            e.hasStarted() || this.startHlsLoad("defer timeout")
                        }), v.HLS_LAZY_LOAD_DELAY);
                        e.one("play", (() => this.startHlsLoad("play")));
                        e.one("seeking", (() => this.startHlsLoad("seeking")))
                    }
                }
                render() {
                    const {
                        status: e,
                        options: t
                    } = this.props, s = "cover" === t.objectFit ? t.objectFit : "contain";
                    return (0, $.jsxs)("div", {
                        "data-vjs-player": "true",
                        "data-status": e.toLowerCase(),
                        "data-has-poster-image": !t.hidePosterImage,
                        "data-test-id": "video-js-wrapper",
                        children: [(0, $.jsx)("video", {
                            ref: this.videoNode,
                            className: "video-js vjs-hubspot vjs-big-play-centered vjs-mux videojs-mux-player-dimensions",
                            controls: !0,
                            crossOrigin: "anonymous",
                            playsInline: !0,
                            preload: "none",
                            style: {
                                objectFit: s
                            }
                        }), (0, $.jsx)(ee.NavMarker, {
                            name: "PLAYER_LOADED"
                        })]
                    })
                }
            }
            re.propTypes = ae;
            re.defaultProps = {
                status: I.PlayerStatus.INITIAL,
                onChangeStatus: () => {},
                onReady: () => {},
                onPlaying: () => {},
                onPause: () => {},
                onEnded: () => {}
            };
            const oe = {
                embedId: n().string,
                params: n().object.isRequired,
                playerType: n().oneOf(Object.values(d.PlayerTypes)),
                video: n().object,
                videoFetchError: n().object,
                appError: n().oneOf(Object.values(I.PlayerErrorTypes)),
                utk: n().string,
                pageMeta: n().object,
                captions: n().array,
                currentTime: n().number,
                embedValidated: n().bool,
                confirmedVisible: n().bool,
                waitForConfirmedVisible: n().bool,
                onCreatePlayer: n().func.isRequired,
                postMessageToParent: n().func.isRequired,
                trackUsage: n().func.isRequired,
                trackInitialPlayUsage: n().func.isRequired,
                areChaptersEnabled: n().bool
            };
            class ne extends a.PureComponent {
                static getDerivedStateFromError() {
                    return {
                        hitErrorBoundary: !0,
                        playerStatus: I.PlayerStatus.ERROR
                    }
                }
                constructor(e) {
                    super(e);
                    this.state = {
                        playerStatus: I.PlayerStatus.INITIAL,
                        hasConverted: !1,
                        hitErrorBoundary: !1
                    };
                    this.postMessageToParent = (e, t) => {
                        this.props.postMessageToParent(e, t)
                    };
                    this.getPlayer = () => {
                        var e;
                        return null === (e = this.videoPlayerRef) || void 0 === e ? void 0 : e.getPlayer()
                    };
                    this.withPlayer = e => {
                        const {
                            embedId: t,
                            videoFetchError: s,
                            appError: i
                        } = this.props, a = this.getPlayer();
                        a ? e.call(this, a) : g().captureMessage("Player not ready", {
                            level: "error",
                            extra: {
                                embedId: t,
                                videoFetchError: s,
                                appError: i
                            }
                        })
                    };
                    this.setRef = e => {
                        const t = this.getConversionAsset();
                        if (e) {
                            this.videoPlayerRef = e;
                            const s = e.getPlayer();
                            this.hasCustomPositionConversionAsset() && t && "number" == typeof t.overlayDelaySeconds && s.on("timeupdate", this.handleTimeUpdate);
                            this.props.onCreatePlayer && this.props.onCreatePlayer(this, s)
                        }
                    };
                    this.handleTimeUpdate = () => {
                        const {
                            overlayDelaySeconds: e
                        } = this.getConversionAsset() || {};
                        this.state.hasConverted || this.state.playerStatus !== I.PlayerStatus.PLAYING || this.withPlayer((t => {
                            if (e && t.currentTime() >= e) {
                                t.pause();
                                this.handleStatusChange(I.PlayerStatus.CONVERSION_ASSET_OVERLAY);
                                t.off("timeupdate", this.handleTimeUpdate)
                            }
                        }))
                    };
                    this.handleStatusChange = (e, t) => {
                        if (e === I.PlayerStatus.ERROR && t) this.props.postMessageToParent(y.MessageTypes.PLAYER_ERROR, {
                            errorType: t.hlsError ? t.hlsError.type : t.type,
                            message: t.message
                        });
                        else if (this.state.playerStatus !== e) {
                            (0, p.debugLog)(`Player ${this.props.embedId} status change: ${this.state.playerStatus} -> ${e}`, t);
                            if (e === I.PlayerStatus.CONVERSION_ASSET_OVERLAY) {
                                const e = this.getConversionAsset();
                                (e && e.type === d.ConversionAssetTypes.CTA || !(0, l.isAuthedAppPreview)()) && this.postMessageToParent(y.MessageTypes.PLAYER_SHOW_OVERLAY, e);
                                let t = e && e.type === d.ConversionAssetTypes.FORM ? "form-displayed" : "cta-displayed";
                                e && (0, w.isWebInteractiveId)(e.id) && (t = "cta-v2-displayed");
                                this.props.trackUsage("playerInteraction", {
                                    action: t
                                })
                            }
                            this.setState({
                                playerStatus: e,
                                videoJsError: t
                            })
                        }
                    };
                    this.handleReady = () => {
                        this.postMessageToParent(y.MessageTypes.PLAYER_READY, null)
                    };
                    this.handlePlaying = () => {
                        if (!this.hasPrePositionConversionAsset() || this.state.hasConverted) {
                            this.postMessageToParent(y.MessageTypes.PLAYER_PLAY, {
                                isInitial: !this.hasPlayed
                            });
                            this.hasPlayed ? this.props.trackUsage("playerInteraction", {
                                action: "resume"
                            }) : this.props.trackInitialPlayUsage();
                            this.hasPlayed = !0
                        } else setTimeout((() => {
                            this.withPlayer((e => {
                                e.pause();
                                e.hasStarted(!1);
                                this.handleStatusChange(I.PlayerStatus.CONVERSION_ASSET_OVERLAY)
                            }))
                        }))
                    };
                    this.handlePause = () => {
                        if (!this.hasPrePositionConversionAsset() || this.state.hasConverted) {
                            this.postMessageToParent(y.MessageTypes.PLAYER_PAUSE, null);
                            "visible" === document.visibilityState && this.props.trackUsage("playerInteraction", {
                                action: "pause"
                            })
                        }
                    };
                    this.handleEnded = () => {
                        this.postMessageToParent(y.MessageTypes.PLAYER_ENDED, null);
                        this.hasPostPositionConversionAsset() && !this.state.hasConverted && this.handleStatusChange(I.PlayerStatus.CONVERSION_ASSET_OVERLAY)
                    };
                    this.handleSkipOverlay = e => {
                        const t = this.getConversionAsset();
                        let s = t && t.type === d.ConversionAssetTypes.FORM ? "form-skipped" : "cta-skipped";
                        e && e.isLegacyForm && (s = "legacy-form-skipped");
                        this.props.trackUsage("playerInteraction", {
                            action: s
                        });
                        this.setState({
                            hasConverted: !0
                        })
                    };
                    this.handlePostConversion = e => {
                        const t = this.getConversionAsset();
                        let s = t && t.type === d.ConversionAssetTypes.FORM ? "form-submit" : "cta-click";
                        e && e.isLegacyForm ? s = "legacy-form-submit" : t && (0, w.isWebInteractiveId)(t.id) && (s = "cta-v2-click");
                        this.props.trackUsage("playerInteraction", {
                            action: s
                        });
                        this.setState({
                            hasConverted: !0
                        })
                    };
                    this.hasPlayed = !1
                }
                componentDidUpdate(e, t) {
                    const {
                        params: s,
                        playerType: i
                    } = this.props;
                    if (i && i !== e.playerType && e.playerType === d.PlayerTypes.EDITOR_PREVIEW && i === d.PlayerTypes.DEFAULT) {
                        (0, p.debugLog)(`Changed playerType for embedId ${this.props.embedId} to ${this.state.playerType}`);
                        this.withPlayer((e => {
                            var t;
                            (0, p.debugLog)("Attempting play after upgrading to videojs", null === (t = e.play()) || void 0 === t ? void 0 : t.catch((e => {
                                console.error("Video playback error", e)
                            })))
                        }))
                    }
                    "number" == typeof this.props.currentTime && e.currentTime !== this.props.currentTime && this.handleCurrentTimeUpdate();
                    this.state.hasConverted && !t.hasConverted && this.withPlayer((e => {
                        if (this.hasPrePositionConversionAsset() || this.hasCustomPositionConversionAsset()) {
                            var t;
                            null === (t = e.play()) || void 0 === t || t.catch((e => {
                                console.error("Video playback error", e)
                            }))
                        } else {
                            if (s.loop) {
                                e.loop(!0);
                                e.currentTime(0)
                            }
                            this.handleStatusChange(I.PlayerStatus.READY)
                        }
                    }))
                }
                componentDidCatch(e, t) {
                    const {
                        video: s
                    } = this.props;
                    console.error("Hit error boundary", e, t);
                    const i = {
                        errorInfo: t,
                        video: s
                    };
                    g().captureException(e, {
                        extra: i,
                        fingerprint: ["PlayerContainer-errorBoundary"]
                    })
                }
                getConversionAsset() {
                    const e = this.state.conversionAsset || this.props.params.conversionAsset;
                    e && (e.copy = {
                        defaultThankYouMessage: A.default.text("videoPlayer.formModal.defaultThankYouMessage"),
                        watchVideo: A.default.text("videoPlayer.formModal.inlineMessageButton")
                    });
                    return e
                }
                hasPrePositionConversionAsset() {
                    const {
                        video: e
                    } = this.props;
                    if (e && !e.isEmbeddable) return !1;
                    const t = this.getConversionAsset();
                    return t && t.id && t.position === d.ConversionAssetPositions.PRE
                }
                hasPostPositionConversionAsset() {
                    const {
                        video: e
                    } = this.props;
                    if (e && !e.isEmbeddable) return !1;
                    const t = this.getConversionAsset();
                    return t && t.id && t.position === d.ConversionAssetPositions.POST
                }
                hasCustomPositionConversionAsset() {
                    const {
                        video: e
                    } = this.props;
                    if (e && !e.isEmbeddable) return !1;
                    const t = this.getConversionAsset();
                    return t && t.id && t.position === d.ConversionAssetPositions.CUSTOM
                }
                handleCurrentTimeUpdate() {
                    const {
                        currentTime: e
                    } = this.props;
                    this.hasCustomPositionConversionAsset() && this.withPlayer((t => {
                        const s = this.getConversionAsset() || {};
                        t.on("timeupdate", this.handleTimeUpdate);
                        this.setState({
                            hasConverted: !1,
                            conversionAsset: Object.assign({}, s, {
                                overlayDelaySeconds: e
                            })
                        })
                    }))
                }
                renderFormOverlay() {
                    const {
                        playerStatus: e
                    } = this.state, {
                        video: t
                    } = this.props, {
                        playButtonColor: s
                    } = this.props.params, i = this.getConversionAsset();
                    return i && i.type === d.ConversionAssetTypes.FORM ? (0, l.isAuthedAppPreview)() ? (0, $.jsx)(Z, {
                        player: this.getPlayer(),
                        status: e,
                        id: i.id,
                        video: t,
                        conversionAsset: i,
                        playButtonColor: s,
                        onPostSubmit: this.handlePostConversion,
                        onSkip: () => this.handleSkipOverlay()
                    }) : e === I.PlayerStatus.CONVERSION_ASSET_OVERLAY ? (0, $.jsx)(K.default, {
                        allowClose: !1,
                        use: "lightbox"
                    }) : null : null
                }
                renderCtaOverlay() {
                    const e = this.getConversionAsset(),
                        {
                            playerStatus: t
                        } = this.state;
                    return e && e.type === d.ConversionAssetTypes.CTA ? t !== I.PlayerStatus.CONVERSION_ASSET_OVERLAY ? null : (0, $.jsx)(K.default, {
                        allowClose: !1,
                        use: "lightbox"
                    }) : null
                }
                renderContent() {
                    const {
                        params: e,
                        playerType: t,
                        appError: s,
                        video: i,
                        videoFetchError: a,
                        pageMeta: r,
                        currentTime: o,
                        captions: n
                    } = this.props, {
                        playerStatus: d,
                        videoJsError: l
                    } = this.state;
                    if (s || a || l || d === I.PlayerStatus.ERROR) {
                        const e = this.getPlayer(),
                            t = Boolean(e && e.currentTime() > 0);
                        return (0, $.jsx)(Q.default, {
                            appError: s,
                            videoFetchError: a,
                            videoJsError: l,
                            hasPlaybackStarted: t
                        })
                    }
                    return (0, $.jsxs)($.Fragment, {
                        children: [(0, $.jsx)(re, {
                            ref: this.setRef,
                            embedId: this.props.embedId,
                            options: e,
                            video: i,
                            title: i.altText || i.title,
                            status: d,
                            playerType: t,
                            pageMeta: r,
                            currentTime: o,
                            hasConverted: this.state.hasConverted,
                            confirmedVisible: this.props.confirmedVisible,
                            waitForConfirmedVisible: this.props.waitForConfirmedVisible,
                            onChangeStatus: this.handleStatusChange,
                            onReady: this.handleReady,
                            onPlaying: this.handlePlaying,
                            onPause: this.handlePause,
                            onEnded: this.handleEnded,
                            postMessageToParent: this.postMessageToParent,
                            trackUsage: this.props.trackUsage,
                            captions: n,
                            areChaptersEnabled: this.props.areChaptersEnabled
                        }), this.getPlayer() && this.renderCtaOverlay(), this.getPlayer() && this.renderFormOverlay()]
                    })
                }
                render() {
                    const {
                        playerStatus: e
                    } = this.state;
                    return (0, $.jsx)("main", {
                        className: "app-fullscreen-embed",
                        "data-application-name": "video-player-ui",
                        "data-status": e.toLowerCase(),
                        children: this.renderContent()
                    })
                }
            }
            ne.propTypes = oe;
            const de = e => ({
                appError: (0, h.getAppError)(e),
                confirmedVisible: (0, h.getConfirmedVisible)(e),
                waitForConfirmedVisible: !(0, l.isAuthedAppPreview)(),
                captions: (0, h.getCaptions)(e),
                areChaptersEnabled: (0, h.getAreChaptersEnabled)(e)
            });
            var le = (0, r.connect)(de)(ne);
            class pe extends a.PureComponent {
                constructor(...e) {
                    super(...e);
                    this.trackedInitialPlay = !1;
                    this.trackInitialPlayUsage = () => {
                        if (!this.trackedInitialPlay) {
                            this.props.trackUsage("playerInteraction", {
                                action: "play"
                            });
                            this.trackedInitialPlay = !0
                        }
                    }
                }
                async componentDidMount() {
                    this.isPublicEmbed() && this.initRetentionTracking();
                    this.props.utk && await this.handleSetUtk();
                    this.props.pageMeta && await this.handleSetPageMeta()
                }
                async componentDidUpdate(e) {
                    const {
                        utk: t,
                        pageMeta: s
                    } = this.props;
                    t === e.utk || (0, l.isAuthedAppPreview)() || await this.handleSetUtk();
                    s && s !== e.pageMeta && await this.handleSetPageMeta()
                }
                isValidEmbed() {
                    const {
                        params: e,
                        debugFlagEnabled: t,
                        pageMeta: s
                    } = this.props;
                    return (0, l.isAuthedAppPreview)() || e.playerType === d.PlayerTypes.RICH_TEXT_PREVIEW || e.playerType === d.PlayerTypes.EDITOR_PREVIEW || Boolean(s && s.pageUrl) || t
                }
                isPublicEmbed() {
                    const {
                        params: e
                    } = this.props;
                    return !(0, l.isAuthedAppPreview)() && e.playerType !== d.PlayerTypes.RICH_TEXT_PREVIEW && e.playerType !== d.PlayerTypes.EDITOR_PREVIEW
                }
                async handleSetPageMeta() {
                    const {
                        pageMeta: e
                    } = this.props, t = (0, L.cleanPageUrl)(e.pageUrl);
                    this.props.tracker.setProperties({
                        pageUrl: t
                    });
                    if (this.reporter) {
                        this.reporter.pageMeta = e;
                        this.videoJsPlayer && this.videoJsPlayer.hasStarted() && await this.reporter.trackPlay()
                    }
                    if (this.isValidEmbed())(0, p.debugLog)(`Validated CMS embed for player: ${this.props.embedId}`);
                    else {
                        console.error("Received invalid payload from SET_PAGE_META, blocking play", {
                            extra: e
                        });
                        f.Metrics.counter("page-meta-payload-invalid").increment()
                    }
                }
                async handleSetUtk() {
                    const {
                        utk: e
                    } = this.props;
                    if (this.reporter) {
                        this.reporter.utk = e;
                        this.videoJsPlayer && this.videoJsPlayer.hasStarted() && await this.reporter.trackPlay()
                    }
                }
                initRetentionTracking() {
                    const {
                        playerType: e,
                        video: t,
                        utk: s,
                        pageMeta: i,
                        postMessageToParent: a
                    } = this.props, r = [d.PlayerTypes.RICH_TEXT_PREVIEW, d.PlayerTypes.EDITOR_PREVIEW, d.PlayerTypes.HTML5];
                    this.reporter || t && !t.isEmbeddable || r.includes(e) || t && this.videoJsPlayer && (this.reporter = R(t, this.videoJsPlayer, {
                        embedId: this.props.embedId,
                        sessionId: (0, c.getSessionId)(),
                        utk: s,
                        pageMeta: i,
                        postMessageToParent: a
                    }, this.props.trackUsage))
                }
                render() {
                    const {
                        embedId: e,
                        params: t,
                        utk: s,
                        pageMeta: i,
                        postMessageToParent: a
                    } = this.props;
                    return (0, $.jsx)(le, {
                        embedId: e,
                        params: t,
                        playerType: this.props.playerType,
                        video: this.props.video,
                        videoFetchError: this.props.videoFetchError,
                        utk: s,
                        pageMeta: i,
                        embedValidated: !0,
                        currentTime: this.props.currentTime,
                        postMessageToParent: a,
                        trackUsage: this.props.trackUsage,
                        trackInitialPlayUsage: this.trackInitialPlayUsage,
                        onCreatePlayer: (e, t) => {
                            this.playerContainer = e;
                            this.videoJsPlayer = t;
                            this.props.video && document.body.classList.add("player-mounted");
                            this.props.onCreatePlayer(e, t)
                        }
                    })
                }
            }
            pe.propTypes = Object.assign({}, oe, {
                debugFlagEnabled: n().bool.isRequired,
                trackInitialPlayUsage: n().func
            });
            const he = e => ({
                video: (0, h.getVideo)(e),
                videoFetchError: (0, h.getVideoFetchError)(e)
            });
            var ce = (0, r.connect)(he)(pe)
        }
    }
]);
//# sourceMappingURL=//static.hsappstatic.net/video-player-ui/static-1.28320/EmbedContainer.js.map