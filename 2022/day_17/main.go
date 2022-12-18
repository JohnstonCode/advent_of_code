package main

import (
	"fmt"
	"image"
	"os"
	"strings"
)

type shape struct {
	pos    image.Point
	points []image.Point
	height int
	width  int
}

var shapes = []shape{
	{points: []image.Point{{}, {1, 0}, {2, 0}, {3, 0}}, height: 1, width: 4},
	{points: []image.Point{{1, 0}, {0, 1}, {1, 1}, {2, 1}, {1, 2}}, height: 3, width: 3},
	{points: []image.Point{{}, {1, 0}, {2, 0}, {2, 1}, {2, 2}}, height: 3, width: 3},
	{points: []image.Point{{}, {0, 1}, {0, 2}, {0, 3}}, height: 4, width: 1},
	{points: []image.Point{{}, {1, 0}, {0, 1}, {1, 1}}, height: 2, width: 2},
}

type key struct {
	depth [7]int
	shape int
	move  int
}

type state struct {
	height  int
	stopped int
}

func main() {
	content, _ := os.ReadFile("./input.txt")
	moves := strings.Split(strings.TrimSpace(string(content)), "")

	fmt.Printf("Part 1: %v\n", play(moves, 2022))
	fmt.Printf("Part 1: %v\n", play(moves, 1000000000000))
}

func play(moves []string, from int) int {
	highestPoint := 0
	width := 7
	movesPos := make([]image.Point, len(moves))
	heightInCycles := 0
	checkPatterns := true

	for i, s := range moves {
		p := image.Point{X: -1, Y: 0}
		if s == ">" {
			p.X = 1
		}

		movesPos[i] = p
	}

	startPos := image.Point{X: 2, Y: 4}
	down := image.Point{X: 0, Y: -1}
	screen := make([]uint8, 1)
	seen := make(map[key]state)

	screen[0] = uint8((1 << width) - 1)
	shapeI := 0
	moveI := 0
	lenShape := len(shapes)
	lenMoves := len(movesPos)

	stoppedCountLeft := from

	for {
		if stoppedCountLeft == 0 {
			break
		}

		s := shapes[shapeI]
		shapeI = (shapeI + 1) % lenShape

		s.pos.X = startPos.X
		s.pos.Y = highestPoint + startPos.Y

		if len(screen) < s.pos.Y+s.height+1 {
			newScreen := make([]uint8, (s.pos.Y+s.height)*1000000)
			copy(newScreen, screen)
			screen = newScreen
		}

		for {
			move := movesPos[moveI]
			moveI = (moveI + 1) % lenMoves

			if shapeCanMove(&s, &move, width, &screen) {
				s.pos.X += move.X
			}

			if shapeCanMove(&s, &down, width, &screen) {
				s.pos.Y--
			} else {
				drawShape(&s, &screen)
				shapeHeight := s.pos.Y + s.height - 1
				highestPoint = max(highestPoint, shapeHeight)
				stoppedCountLeft--

				if checkPatterns {
					depthMap := calcDepthMap(&screen, highestPoint)
					k := key{shape: shapeI, move: moveI, depth: depthMap}
					prevS, ok := seen[k]

					if ok {
						diffHeight := highestPoint - prevS.height
						cycleLength := prevS.stopped - stoppedCountLeft

						countCyclesLeft := stoppedCountLeft / cycleLength
						heightInCycles = diffHeight * countCyclesLeft

						stoppedCountLeft = stoppedCountLeft % cycleLength
						checkPatterns = false
					} else {
						seen[k] = state{height: highestPoint, stopped: stoppedCountLeft}
					}
				}

				break
			}
		}
	}

	return heightInCycles + highestPoint
}

func shapeCanMove(s *shape, move *image.Point, width int, screen *[]uint8) bool {
	px := s.pos.X + move.X
	py := s.pos.Y + move.Y

	for _, i := range s.points {
		x := px + i.X
		if x < 0 || x >= width {
			return false
		}

		y := py + i.Y
		if (*screen)[y]&(1<<x) != 0 {
			return false
		}
	}

	return true
}

func drawShape(s *shape, screen *[]uint8) {
	for _, i := range s.points {
		x := s.pos.X + i.X
		y := s.pos.Y + i.Y
		(*screen)[y] |= 1 << x
	}
}

func max(a int, b int) int {
	if a > b {
		return a
	}

	return b
}

func calcDepthMap(screen *[]uint8, top int) [7]int {
	res := [7]int{}
	for i := 0; i < 7; i++ {
		m := uint8(1 << i)
		for j := top; j >= 0; j-- {
			if (*screen)[j]&m != 0 {
				res[i] = top - j
				break
			}
		}
	}
	return res
}
