package main

import (
	"fmt"
	"image"
	"os"
	"regexp"
	"strconv"
	"strings"
)

func main() {
	content, _ := os.ReadFile("./input.txt")
	parts := strings.Split(string(content), "\n\n")
	grid := make(map[image.Point]string)
	maxX, maxY, minX, minY := 0, 0, 1, 1

	for y, line := range strings.Split(parts[0], "\n") {
		maxY = max(maxY, y+1)
		for x, v := range strings.Split(line, "") {
			maxX = max(maxX, x+1)
			if v == " " {
				continue
			}

			grid[image.Point{X: x + 1, Y: y + 1}] = v
		}
	}

	var currentPoint image.Point

loop:
	for y := 1; y <= len(strings.Split(parts[0], "\n")); y++ {
		for x := 1; x <= len(strings.Split(parts[0], "\n")[0]); x++ {
			if p, ok := grid[image.Point{X: x, Y: y}]; ok && p == "." {
				currentPoint = image.Point{X: x, Y: y}
				break loop
			}
		}
	}

	re := regexp.MustCompile(`\d+|L|R`)
	dirs := re.FindAllString(parts[1], -1)

	currentDir := 0
	faces := map[int]image.Point{
		3: {X: 0, Y: -1}, //Up
		0: {X: 1, Y: 0},  //Right
		1: {X: 0, Y: 1},  //Down
		2: {X: -1, Y: 0}, //Left
	}

	for _, dir := range dirs {
		if dir == "L" {
			currentDir = getNewDir(currentDir, -1)

			continue
		}

		if dir == "R" {
			currentDir = getNewDir(currentDir, 1)

			continue
		}

		steps, _ := strconv.Atoi(dir)

	lloop:
		for i := 0; i < steps; i++ {
			np := currentPoint.Add(faces[currentDir])

			if p, ok := grid[np]; ok && p == "." {
				currentPoint = np
				continue
			} else if ok && p == "#" {
				break
			} else {
				//find new point
				var nnp image.Point

				if currentDir == 3 {
					//start at bottom
					nnp = image.Point{X: np.X, Y: maxY}
				} else if currentDir == 0 {
					//start left
					nnp = image.Point{X: minX, Y: np.Y}
				} else if currentDir == 1 {
					//start top
					nnp = image.Point{X: np.X, Y: minY}
				} else {
					//start right
					nnp = image.Point{X: maxX, Y: np.Y}
				}

				if pp, pok := grid[nnp]; pok && pp == "." {
					currentPoint = nnp
					continue
				} else if pok && pp == "#" {
					break
				}

				for {
					nnp = nnp.Add(faces[currentDir])

					if ppp, ppok := grid[nnp]; ppok && ppp == "." {
						currentPoint = nnp
						continue lloop
					} else if ppok && ppp == "#" {
						break lloop
					}
				}
			}
		}
	}

	fmt.Println(1000*currentPoint.Y + 4*currentPoint.X + currentDir)

llloop:
	for y := 1; y <= len(strings.Split(parts[0], "\n")); y++ {
		for x := 1; x <= len(strings.Split(parts[0], "\n")[0]); x++ {
			if p, ok := grid[image.Point{X: x, Y: y}]; ok && p == "." {
				currentPoint = image.Point{X: x, Y: y}
				break llloop
			}
		}
	}

	currentDir = 0

	for _, dir := range dirs {
		if dir == "L" {
			currentDir = getNewDir(currentDir, -1)

			continue
		}

		if dir == "R" {
			currentDir = getNewDir(currentDir, 1)

			continue
		}

		steps, _ := strconv.Atoi(dir)

		for i := 0; i < steps; i++ {
			np := currentPoint.Add(faces[currentDir])

			if p, ok := grid[np]; ok && p == "." {
				currentPoint = np
				continue
			} else if ok && p == "#" {
				break
			} else {
				var nnp image.Point
				var nf int

				if currentDir == 3 { //up
					if currentPoint.X <= 50 { //4
						nf = 0
						nnp = image.Point{X: currentPoint.Y - 50, Y: currentPoint.X + 50}
					} else if currentPoint.X <= 100 { // 1
						nf = 0
						nnp = image.Point{X: minX, Y: currentPoint.X + 100}
					} else { // 2
						nf = 3
						nnp = image.Point{X: currentPoint.X - 100, Y: maxY}
					}
				} else if currentDir == 0 { // right
					if currentPoint.Y <= 50 { // 2
						nf = 2
						nnp = image.Point{X: currentPoint.X - 50, Y: 151 - currentPoint.Y}
					} else if currentPoint.Y <= 100 { // 3
						nf = 3
						nnp = image.Point{X: currentPoint.Y + 50, Y: currentPoint.X - 50}
					} else if currentPoint.Y <= 150 { // 5
						nf = 2
						nnp = image.Point{X: maxX, Y: currentPoint.Y - 100}
					} else { // 6
						nf = 3
						nnp = image.Point{X: currentPoint.Y - 100, Y: currentPoint.X + 100}
					}
				} else if currentDir == 1 { // down
					if currentPoint.X <= 50 { // 6
						nf = 1
						nnp = image.Point{X: currentPoint.X + 100, Y: minY}
					} else if currentPoint.X <= 100 { // 5
						nf = 2
						nnp = image.Point{X: currentPoint.Y - 100, Y: currentPoint.X + 100}
					} else if currentPoint.X <= 150 { // 2
						nf = 2
						nnp = image.Point{X: currentPoint.Y + 50, Y: currentPoint.X - 50}
					}
				} else { // left
					if currentPoint.Y <= 50 { // 1
						nf = 0
						nnp = image.Point{X: minX, Y: currentPoint.Y + 100}
					} else if currentPoint.Y <= 100 { // 3
						nf = 1
						nnp = image.Point{X: currentPoint.Y - 50, Y: currentPoint.X + 50}
					} else if currentPoint.Y <= 150 { // 4
						nf = 0
						nnp = image.Point{X: currentPoint.X + 50, Y: currentPoint.Y - 100}
					} else { // 6
						nf = 1
						nnp = image.Point{X: currentPoint.Y - 100, Y: minY}
					}
				}

				if pp, pok := grid[nnp]; pok && pp == "." {
					currentPoint = nnp
					currentDir = nf
					continue
				} else if pok && pp == "#" {
					break
				}
			}
		}
	}

	fmt.Println(1000*currentPoint.Y + 4*currentPoint.X + currentDir)
}

func getNewDir(c int, n int) int {
	nd := c + n

	if nd > 3 {
		return 0
	}

	if nd < 0 {
		return 3
	}

	return nd
}

func max(a, b int) int {
	if a > b {
		return a
	}

	return b
}
